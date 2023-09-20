<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 November 2017
 * File Name	= M_position_func.php
 * Location		= -
*/

class M_position_func extends CI_Model
{
	function count_all() // U
	{
		return $this->db->count_all('tbl_position_func');
	}
	
	function get_position_func() // U
	{		
		$sql = "SELECT * FROM tbl_position_func";
		return $this->db->query($sql);
	}
	
	function get_position_func_prn() // U
	{
		$sql = "SELECT * FROM tbl_position_func WHERE POSF_PARENT = ''";
		return $this->db->query($sql);
	}
	
	function count_all_str() // U
	{
		$sql = "SELECT * FROM tbl_position_str";
		return $this->db->query($sql);
	}
	
	function get_position_str() // U
	{
		$sql = "SELECT * FROM tbl_position_str WHERE POSS_PARENT = ''";
		return $this->db->query($sql);
	}
	
	function get_position_by_code($POSF_CODE) // U
	{
		$sql = "SELECT * FROM tbl_position_func WHERE POSF_CODE = '$POSF_CODE'";
		return $this->db->query($sql);
	}
	
	function add($position) // U
	{
		$this->db->insert('tbl_position_func', $position);
	}
	
	function update($POSF_NO, $position) // U
	{
		$this->db->where('POSF_NO', $POSF_NO);
		$this->db->update('tbl_position_func', $position);
	}
}
?>