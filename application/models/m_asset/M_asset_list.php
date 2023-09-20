<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Maret 2017
 * File Name	= M_asset_list.php
 * Location		= -
*/
?>
<?php
class M_asset_list extends CI_Model
{	
	function count_all_num_rows() // OK
	{
		$sql		= "tbl_asset_list";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AG() // OK
	{	
		$sql = "SELECT *
				FROM tbl_asset_list WHERE AS_STAT != 9
				ORDER BY AS_NAME";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($InsAG) // OK
	{
		$this->db->insert('tbl_asset_list', $InsAG);
	}
				
	function get_AG($AS_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_asset_list
				WHERE AS_CODE = '$AS_CODE'";
		return $this->db->query($sql);
	}
	
	function update($AS_CODE, $UpdAG) // OK
	{
		$this->db->where('AS_CODE', $AS_CODE);
		$this->db->update('tbl_asset_list', $UpdAG);
	}
}
?>