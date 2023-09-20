<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 29 Maret 2017
 * File Name	= M_menu.php
 * Location		= -
*/
?>
<?php
class M_menu extends CI_Model
{
	function count_all_num_rows() // OK
	{
		return $this->db->count_all('tbl_menu');
	}
	
	function get_last_ten_menu() // OK
	{		
		$sql = "SELECT * FROM tbl_menu";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function getCount_forParent() // OK
	{
		$sql		= "tbl_menu WHERE level_menu = '1'";
		return $this->db->count_all($sql);
	}

	function get_forParent() // OK
	{
		$sql = "SELECT * FROM tbl_menu WHERE level_menu = '1' order by no_urut";
		return $this->db->query($sql);
	}
	
	function add($insMenu) // OK
	{
		$this->db->insert('tbl_menu', $insMenu);
	}
	
	function get_menu_by_code($menu_code) // OK
	{
		$sql = "SELECT * FROM tbl_menu WHERE menu_code = '$menu_code'";
		return $this->db->query($sql);
	}
	
	function update($menu_code, $insMenu) // OK
	{
		$this->db->where('menu_code', $menu_code);
		$this->db->update('tbl_menu', $insMenu);
	}
}
?>