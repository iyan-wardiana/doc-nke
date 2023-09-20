<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 1 November 2017
 * File Name	= M_position_str.php
 * Location		= -
*/

class M_position_str extends CI_Model
{
	function count_all() // U
	{
		return $this->db->count_all('tbl_position_str');
	}
		
	function get_AllDataC($search) // GOOD
	{
		$sql = "tbl_position_str
				WHERE POSS_CODE LIKE '%$search%' ESCAPE '!' OR POSS_NAME LIKE '%$search%' ESCAPE '!'
					OR POSS_DESC LIKE '%$search%' ESCAPE '!'";
		return $this->db->count_all($sql);
	}
		
	function get_AllDataL($search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT *
						FROM tbl_position_str
						WHERE POSS_CODE LIKE '%$search%' ESCAPE '!' OR POSS_NAME LIKE '%$search%' ESCAPE '!'
							OR POSS_DESC LIKE '%$search%' ESCAPE '!'
						ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT *
						FROM tbl_position_str
						WHERE POSS_CODE LIKE '%$search%' ESCAPE '!' OR POSS_NAME LIKE '%$search%' ESCAPE '!'
							OR POSS_DESC LIKE '%$search%' ESCAPE '!'";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT *
						FROM tbl_position_str
						WHERE POSS_CODE LIKE '%$search%' ESCAPE '!' OR POSS_NAME LIKE '%$search%' ESCAPE '!'
							OR POSS_DESC LIKE '%$search%' ESCAPE '!'
						ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT *
						FROM tbl_position_str
						WHERE POSS_CODE LIKE '%$search%' ESCAPE '!' OR POSS_NAME LIKE '%$search%' ESCAPE '!'
							OR POSS_DESC LIKE '%$search%' ESCAPE '!'
						LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_position_str() // U
	{		
		$sql = "SELECT * FROM tbl_position_str";
		return $this->db->query($sql);
	}
	
	function get_position_str_prn() // U
	{
		//$sql = "SELECT * FROM tbl_position_str WHERE POSS_PARENT = 0";
		$sql = "SELECT * FROM tbl_position_str WHERE POSS_ISLAST = 0";
		return $this->db->query($sql);
	}
	
	function get_position_by_code($POSS_CODE) // U
	{
		$sql = "SELECT * FROM tbl_position_str WHERE POSS_CODE = '$POSS_CODE'";
		return $this->db->query($sql);
	}
	
	function add($position) // U
	{
		$this->db->insert('tbl_position_str', $position);
	}
	
	function update($POSS_CODE, $position) // U
	{
		$this->db->where('POSS_CODE', $POSS_CODE);
		$this->db->update('tbl_position_str', $position);
	}
}
?>