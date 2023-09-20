<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 30 Maret 2019
 * File Name	= m_unittype.php
 * Location		= -
*/

class M_unittype extends CI_Model
{
	function count_all_num_rows() // OK
	{
		return $this->db->count_all('tbl_unittype');
	}
	
	function get_all_data() // OK
	{
		$sql = "SELECT * FROM tbl_unittype";
		return $this->db->query($sql);
	}
	
	function count_ml_code($Unit_Type_Code) // OK
	{
		$sql	= "tbl_unittype WHERE Unit_Type_Code = '$Unit_Type_Code'";
		return $this->db->count_all($sql);
	}
	
	function add($InsML) //OK
	{
		$this->db->insert('tbl_unittype', $InsML);
	}
	
	function get_unit($Unit_Type_ID) // OK
	{		
		$sql = "SELECT * FROM tbl_unittype WHERE Unit_Type_ID = '$Unit_Type_ID'";
		return $this->db->query($sql);
	}
	
	function update($Unit_Type_Code, $UpdML) // OK
	{
		$this->db->where('Unit_Type_Code', $Unit_Type_Code);
		$this->db->update('tbl_unittype', $UpdML);
	}
}
?>