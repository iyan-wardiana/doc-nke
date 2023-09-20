<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Maret 2017
 * File Name	= M_position.php
 * Location		= -
*/
?>
<?php
class M_position extends CI_Model
{
	function count_all_num_rows() // OK
	{
		return $this->db->count_all('tbl_position');
	}
	
	function get_last_ten_position() // OK
	{		
		$sql = "SELECT * FROM tbl_position";
		return $this->db->query($sql);
	}
	
	function getCount_department() // OK
	{
		$this->db->where('DEPINIT', 1);
		return $this->db->count_all('tbl_department');
	}

	function get_department() // OK
	{
		$sql = "SELECT * FROM tbl_department";
		return $this->db->query($sql);
	}
	
	function getCount_position_forParent() // OK
	{
		$this->db->where('POS_ISLAST', 0);
		return $this->db->count_all('tbl_position');
	}

	function get_position_forParent() // OK
	{
		$sql = "SELECT * FROM tbl_position WHERE POS_ISLAST = 0";
		return $this->db->query($sql);
	}
	
	function add($position) // OK
	{
		$this->db->insert('tbl_position', $position);
	}
	
	function get_position_by_code($POS_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_position WHERE POS_CODE = '$POS_CODE'";
		return $this->db->query($sql);
	}
	
	function update($POS_CODE, $position) // OK
	{
		$this->db->where('POS_CODE', $POS_CODE);
		$this->db->update('tbl_position', $position);
	}
}
?>