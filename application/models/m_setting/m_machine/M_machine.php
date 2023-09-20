<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 26 April 2019
 * File Name	= M_machine.php
 * Location		= -
*/

class M_machine extends CI_Model
{
	
	function count_all_STEP() // GOOD
	{
		$sql	= "tbl_machine";
		return $this->db->count_all($sql);
	}
	
	function get_all_STEP() // GOOD
	{
		$sql = "SELECT * FROM tbl_machine ORDER BY MCN_NAME ASC";		
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // GOOD
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($AddMCN) // GOOD
	{
		$this->db->insert('tbl_machine', $AddMCN);
	}
	
	function get_stp_by_number($MCN_NUM) // GOOD
	{			
		$sql = "SELECT * FROM tbl_machine WHERE MCN_NUM = '$MCN_NUM'";
		return $this->db->query($sql);
	}
	
	function updateMCN($MCN_NUM, $UpdMCN) // GOOD
	{
		$this->db->where('MCN_NUM', $MCN_NUM);
		$this->db->update('tbl_machine', $UpdMCN);
	}
}
?>