<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 7 Februari 2017
 * File Name	= M_docpattern_sd.php
 * Location		= -
*/
?>
<?php
class M_docpattern extends CI_Model
{	
	function count_all_num_rows() // U
	{
		return $this->db->count_all('tbl_docpattern');
	}
	
	function get_last_ten_docpattern($limit, $offset) // U
	{
		$this->db->select('Pattern_ID, Pattern_Code,Pattern_Position,Pattern_Name,menu_code,Pattern_NameEdited,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate,Pattern_LengthYear');
		$this->db->from('tbl_docpattern');
		$this->db->order_by('Pattern_Name', 'asc');
		return $this->db->get();
	}
	
	function count_all_num_rows_PatCode($txtSearch) // U
	{
		$sql	= "tbl_docpattern WHERE Pattern_Code LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_PatName($txtSearch) // U
	{
		$sql	= "tbl_docpattern WHERE Pattern_Name LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_docpattern_PatCode($limit, $offset, $txtSearch) // U
	{		
		$sql = "SELECT * FROM tbl_docpattern WHERE Pattern_Code LIKE '%$txtSearch%'
				ORDER BY Pattern_Code";
		return $this->db->query($sql);
	}
	
	function get_last_ten_docpattern_PatName($limit, $offset, $txtSearch) // U
	{		
		$sql = "SELECT * FROM tbl_docpattern WHERE Pattern_Name LIKE '%$txtSearch%'
				ORDER BY Pattern_Code";
		return $this->db->query($sql);
	}
		
	function get_MenuToPattern() // U
	{
		$isNeedPattern = 1;
		$this->db->select('menu_id, menu_code, menu_name_IND, menu_name_ENG');
		$this->db->from('tbl_menu');
		$this->db->where('isNeedPattern', $isNeedPattern);
		$this->db->order_by('menu_name_IND', 'asc');
		return $this->db->get();
	}
	
	function add($docpattern) //U
	{
		$this->db->insert('tbl_docpattern', $docpattern);
	}
	
	function get_docpatern_by_code($Pattern_Code) // U
	{
		$this->db->select('Pattern_ID, Pattern_Code,Pattern_Position,Pattern_Name,menu_code,Pattern_NameEdited,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate,Pattern_LengthYear');
		$this->db->where('Pattern_Code', $Pattern_Code);
		return $this->db->get('tbl_docpattern');
	}
	
	function update($Pattern_ID, $docpattern) // U
	{
		$this->db->where('Pattern_ID', $Pattern_ID);
		$this->db->update('tbl_docpattern', $docpattern);
	}
	
	function delete($Pattern_Code) // HOLD
	{
		// Customer can not be deleted. So, just change status
		$this->db->where('Pattern_Code', $Pattern_Code);
		$this->db->update('tbl_docpattern');
	}
}
?>