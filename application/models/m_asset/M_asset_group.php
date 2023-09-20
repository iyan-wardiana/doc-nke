<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 Maret 2017
 * File Name	= M_asset_group.php
 * Location		= -
*/
?>
<?php
class M_asset_group extends CI_Model
{	
	function count_all_num_rows() // OK
	{
		$sql		= "tbl_asset_group";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AG() // OK
	{	
		$sql = "SELECT AG_CODE, AG_MANCODE, AG_NAME, AG_DESC
				FROM tbl_asset_group
				ORDER BY AG_NAME";
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
		$this->db->insert('tbl_asset_group', $InsAG);
	}
				
	function get_AG($AG_CODE) // OK
	{
		$sql = "SELECT AG_CODE, AG_MANCODE, AG_NAME, AG_DESC FROM tbl_asset_group
				WHERE AG_CODE = '$AG_CODE'";
		return $this->db->query($sql);
	}
	
	function update($AG_CODE, $UpdAG) // OK
	{
		$this->db->where('AG_CODE', $AG_CODE);
		$this->db->update('tbl_asset_group', $UpdAG);
	}
}
?>