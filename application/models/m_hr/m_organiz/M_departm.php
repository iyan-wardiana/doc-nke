<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 November 2017
 * File Name	= M_departm.php
 * Location		= -
*/

class M_departm extends CI_Model
{
	function count_all_dept() // OK
	{
		return $this->db->count_all('tbl_department');
	}
	
	function get_all_department() // OK
	{		
		$sql = "SELECT * FROM tbl_department";
		return $this->db->query($sql);
	}
	
	function add($department) // OK
	{
		$this->db->insert('tbl_department', $department);
	}
	
	function get_department_by_code($DEPCODE) // OK
	{
		$sql = "SELECT * FROM tbl_department WHERE DEPCODE = '$DEPCODE'";
		return $this->db->query($sql);
	}
	
	function update($DEPCODE, $department) // OK
	{
		$this->db->where('DEPCODE', $DEPCODE);
		$this->db->update('tbl_department', $department);
	}
}
?>