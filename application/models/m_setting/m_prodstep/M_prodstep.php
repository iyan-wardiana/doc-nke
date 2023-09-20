<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 22 Oktober 2018
 * File Name	= M_prodstep.php
 * Location		= -
*/

class M_prodstep extends CI_Model
{
	
	function count_all_STEP($PRJCODE) // GOOD
	{
		$sql	= "tbl_prodstep";
		return $this->db->count_all($sql);
	}
	
	function get_all_STEP($PRJCODE) // GOOD
	{
		$sql = "SELECT * FROM tbl_prodstep ORDER BY PRODS_ORDER ASC";		
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // GOOD
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($AddSTP) // GOOD
	{
		$this->db->insert('tbl_prodstep', $AddSTP);
	}
	
	function get_stp_by_number($PRODS_NUM) // GOOD
	{			
		$sql = "SELECT * FROM tbl_prodstep WHERE PRODS_NUM = '$PRODS_NUM'";
		return $this->db->query($sql);
	}
	
	function updateSTP($PRODS_NUM, $UpdSTP) // GOOD
	{
		$this->db->where('PRODS_NUM', $PRODS_NUM);
		$this->db->update('tbl_prodstep', $UpdSTP);
	}
}
?>