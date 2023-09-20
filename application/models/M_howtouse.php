<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 9 Mei 2017
 * File Name	= M_office_HTUentory.php
 * Location		= -
*/
?>
<?php
class m_howtouse extends CI_Model
{	
	function count_all_num_rows() // OK
	{
		$sql		= "tbl_htu";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AG() // OK
	{	
		$sql = "SELECT *
				FROM tbl_htu
				ORDER BY HTU_CODE";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsAllItem() // OK
	{
		return $this->db->count_all('tbl_office_inventory');
	}
	
		function viewAllItemMatBudget() // OK
	{		
		$sql		= "select * from tbl_office_inventory ORDER BY inv_code";
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
		$this->db->insert('tbl_htu', $Ins);
	}
				
	function get_AG($HTU_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_htu
				WHERE HTU_CODE = '$HTU_CODE'";
		return $this->db->query($sql);
	}
	
	function update($HTU_CODE, $Upd) // OK
	{
		$this->db->where('HTU_CODE', $HTU_CODE);
		$this->db->update('tbl_htu', $Upd);
	}
}
?>