<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 9 Mei 2017
 * File Name	= M_office_inventory.php
 * Location		= -
*/
?>
<?php
class M_Office_inventory extends CI_Model
{	
	function count_all_num_rows() // OK
	{
		$sql		= "tbl_office_inventory";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AG() // OK
	{	
		$sql = "SELECT *
				FROM tbl_office_inventory
				ORDER BY INV_NAME";
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
		$this->db->insert('tbl_office_inventory', $Ins);
	}
				
	function get_AG($INV_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_office_inventory
				WHERE INV_CODE = '$INV_CODE'";
		return $this->db->query($sql);
	}
	
	function update($INV_CODE, $Upd) // OK
	{
		$this->db->where('INV_CODE', $INV_CODE);
		$this->db->update('tbl_office_inventory', $Upd);
	}
}
?>