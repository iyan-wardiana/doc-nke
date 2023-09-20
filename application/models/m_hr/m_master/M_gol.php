<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 31 Oktober 2017
 * File Name	= M_gol.php
 * Location		= -
*/

class M_gol extends CI_Model
{	
	function count_all_gol() // U
	{
		$sql	= "tbl_employee_gol";
		return $this->db->count_all($sql);
	}
	
	function get_all_gol() // U
	{
		$sql	= "SELECT * FROM tbl_employee_gol ORDER BY EMPG_PARENT, EMPG_CODE ASC";
		return $this->db->query($sql);
	}
	
	function count_child_code($CHILDC) // U
	{
		$sql	= "tbl_employee_gol WHERE EMPG_CHILD = '$CHILDC' AND EMPG_STAT = 3";
		return $this->db->count_all($sql);
	}
	
	function add($inpGol) // U
	{
		$this->db->insert('tbl_employee_gol', $inpGol);
	}
	
	function get_data_by_Code($EMPG_CODE) // U
	{
		$sql	= "SELECT * FROM tbl_employee_gol WHERE EMPG_CODE = '$EMPG_CODE'";
		return $this->db->query($sql);
	}
	
	function update($EMPG_CODE, $inpGol) // U
	{
		$this->db->where('EMPG_CODE', $EMPG_CODE);
		$this->db->update('tbl_employee_gol', $inpGol);
	}
	
	function getDataDocPat($MenuCode) // U
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tdocpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
}
?>