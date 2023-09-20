<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 2 November 2017
 * File Name	= M_docpattern.php
 * Location		= -
*/

class M_docpattern extends CI_Model
{
	function getDataDocPat($MenuCode) // U
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
}
?>