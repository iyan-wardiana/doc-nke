<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 Maret 2019
 * File Name	= M_wh.php
 * Location		= -
*/

class M_wh extends CI_Model
{
	function count_all_wh() // GOOD
	{
		return $this->db->count_all('tbl_warehouse');
	}
	
	function get_all_wh() // GOOD
	{
		$sql = "SELECT * FROM tbl_warehouse";
		return $this->db->query($sql);
	}
	
	function add($InsWH) // GOOD
	{
		$this->db->insert('tbl_warehouse', $InsWH);
	}
	
	function get_wh($WH_NUM) // GOOD
	{		
		$sql = "SELECT * FROM tbl_warehouse WHERE WH_NUM = '$WH_NUM'";
		return $this->db->query($sql);
	}
	
	function update($WH_NUM, $UpdWH) // GOOD
	{
		$this->db->where('WH_NUM', $WH_NUM);
		$this->db->update('tbl_warehouse', $UpdWH);
	}
}
?>