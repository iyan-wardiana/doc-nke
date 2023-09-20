<?php
/* 
 * Author		= Hendar Permana
 * Create Date	= 14 Juni 2017
 * File Name	= M_entry_provit.php
 * Location		= -
*/
?>
<?php
class M_entry_provit extends CI_Model
{	
	function count_all_num_rows() // OK
	{
		$sql		= "tbl_profloss_man";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AG() // OK
	{	
		$sql = "SELECT *
				FROM tbl_profloss_man
				ORDER BY CODE_PROFLOSS";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($Ins) // OK
	{
		$this->db->insert('tbl_profloss_man', $Ins);
	}
				
	function get_AG($CODE_PROFLOSS) // OK
	{
		$sql = "SELECT * FROM tbl_profloss_man
				WHERE CODE_PROFLOSS = '$CODE_PROFLOSS'";
		return $this->db->query($sql);
	}
	
	function update($CODE_PROFLOSS, $Upd) // OK
	{
		$this->db->where('CODE_PROFLOSS', $CODE_PROFLOSS);
		$this->db->update('tbl_profloss_man', $Upd);
	}
}
?>