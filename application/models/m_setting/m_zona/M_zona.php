<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Januari 2018
 * File Name	= M_Zona.php
 * Location		= -
*/

class M_Zona extends CI_Model
{
	function count_all_num_rows() // OK
	{
		return $this->db->count_all('tbl_zone');
	}
	
	function get_all_zone() // OK
	{
		$sql = "SELECT * FROM tbl_zone";
		return $this->db->query($sql);
	}
	
	function add($InsZN) //OK
	{
		$this->db->insert('tbl_zone', $InsZN);
	}
	
	function count_zb_code($ZN_CODE) // OK
	{
		$sql	= "tbl_zone WHERE ZN_CODE = '$ZN_CODE'";
		return $this->db->count_all($sql);
	}
	
	function get_zone($ZN_ID) // OK
	{		
		$sql = "SELECT * FROM tbl_zone WHERE ZN_ID = '$ZN_ID'";
		return $this->db->query($sql);
	}
	
	function update($ZN_CODE, $InsZN) // OK
	{
		$this->db->where('ZN_CODE', $ZN_CODE);
		$this->db->update('tbl_zone', $InsZN);
	}
}
?>