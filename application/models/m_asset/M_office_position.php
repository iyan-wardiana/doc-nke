<?php
/* 
 * Author		= Hendar Permana
 * Create Date	= 24 Mei 2017
 * File Name	= M_office_position.php
 * Location		= -
*/
?>
<?php
class M_Office_position extends CI_Model
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
				ORDER BY INV_ID";
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
		$this->db->insert('tbl_office_position', $Ins);
	}
				
	function get_AG($COMM_CODE) // OK
	{
		$CODE = explode("|", $COMM_CODE);
		echo $CODE[0]; // ROOM
		echo $CODE[1]; // INV
		
		$sql = "SELECT * FROM tbl_office_position
				WHERE INV_CODE = '$CODE[1]' and ROOM_CODE='$CODE[0]'";
		return $this->db->query($sql);
	}
	
	function update($POS_CODE, $Upd) // OK
	{
		$this->db->where('POS_CODE', $POS_CODE);
		$this->db->update('tbl_office_position', $Upd);
	}
}
?>