<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 4 April 2017
 * File Name	= M_itemcategory.php
 * Location		= -
*/

class M_itemcategory extends CI_Model
{
	function count_all_num_rows() // OK
	{
		return $this->db->count_all('tbl_itemcategory');
	}

	function get_itemcategory() // OK
	{
		$sql = "SELECT * FROM tbl_itemcategory";
		return $this->db->query($sql);
	}
	
	function get_itemcat_by_code($IC_CODE) // OK
	{
		$sql	= "SELECT * FROM  tbl_itemcategory
					WHERE IC_Code = '$IC_CODE'";
		return $this->db->query($sql);
	}
	
	function add($itemcat) // OK
	{
		$this->db->insert('tbl_itemcategory', $itemcat);
	}
	
	function update($IC_Num, $itemcat) // OK
	{
		$this->db->where('IC_Num', $IC_Num);
		$this->db->update('tbl_itemcategory', $itemcat);
	}
}
?>