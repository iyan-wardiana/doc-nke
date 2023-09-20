<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Desember 2017
 * File Name	= M_itemgroup.php
 * Location		= -
*/

class M_itemgroup extends CI_Model
{
	function count_all_num_rows() // OK
	{
		return $this->db->count_all('tbl_itemgroup');
	}

	function get_itemgroup() // OK
	{
		$sql = "SELECT * FROM tbl_itemgroup";
		return $this->db->query($sql);
	}
	
	function get_itemgrp_by_code($IG_Num) // OK
	{
		$sql	= "SELECT * FROM  tbl_itemgroup
					WHERE IG_Num = '$IG_Num'";
		return $this->db->query($sql);
	}
	
	function add($itemgrp) // OK
	{
		$this->db->insert('tbl_itemgroup', $itemgrp);
	}
	
	function update($IG_Num, $itemgrp) // OK
	{
		$this->db->where('IG_Num', $IG_Num);
		$this->db->update('tbl_itemgroup', $itemgrp);
	}
}
?>