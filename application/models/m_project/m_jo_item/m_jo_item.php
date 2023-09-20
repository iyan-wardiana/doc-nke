<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 9 Mei 2017
 * File Name	= M_office_inventory.php
 * Location		= -
*/

/*  
 * Author		= Hendar Permana 
 * Create Date	= 19 Oktober 2017
 * File Name	= m_jo_item.php
 * Location		= -
*/

?>
<?php
class m_jo_item extends CI_Model
{	
	function count_all_num_rows() // OK
	{
		$sql		= "tbl_jo_item";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AG() // OK
	{	
		$sql = "SELECT *
				FROM tbl_jo_item
				ORDER BY JOI_ITEM";
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
		$this->db->insert('tbl_jo_item', $Ins);
	}
				
	function get_AG($JOI_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_jo_item
				WHERE JOI_CODE = '$JOI_CODE'";
		return $this->db->query($sql);
	}
	
	function update($JOI_CODE, $Upd) // OK
	{
		$this->db->where('JOI_CODE', $JOI_CODE);
		$this->db->update('tbl_jo_item', $Upd);
	}
}
?>