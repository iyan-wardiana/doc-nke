<?php
/*  
 * Author		= Hendar Permana 
 * Create Date	= 9 Mei 2017
 * File Name	= M_office_inventory.php
 * Location		= -
*/

/*  
 * Author		= Hendar Permana 
 * Create Date	= 02 Maret 2018
 * File Name	= m_vehicle.php
 * Location		= -
*/

?>
<?php
class m_driver extends CI_Model
{
	function count_all_num_rows() // OK
	{
		$sql		= "tbl_driver";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_AG() // OK
	{	
		$sql = "SELECT *
				FROM tbl_driver
				ORDER BY DRIV_CODE";
		return $this->db->query($sql);
	}

	function getDataDocPatC($MenuCode) // OK
	{
		$sql		= "tbl_docpattern WHERE menu_code = '$MenuCode'";
		return $this->db->count_all($sql);
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
		$this->db->insert('tbl_driver', $Ins);
	}
				
	function get_AG($DRIV_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_driver
				WHERE DRIV_CODE = '$DRIV_CODE'";
		return $this->db->query($sql);
	}
	
	function update($DRIV_CODE, $Upd) // OK
	{
		$this->db->where('DRIV_CODE', $DRIV_CODE);
		$this->db->update('tbl_driver', $Upd);
	}
	
	function deleteDR($DRIV_CODE) // USED
	{
		$this->db->where('DRIV_CODE', $DRIV_CODE);
		$this->db->delete('tbl_driver');
	}
}
?>