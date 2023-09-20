<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 18 Oktober 2018
 * File Name	= M_custcat.php
 * Location		= -
*/

class M_custcat extends CI_Model
{
	function count_all() // G
	{
		return $this->db->count_all('tbl_custcat');
	}
	
	function g37_4ll_cu5tc47() // G
	{
		$sql = "SELECT *
				FROM tbl_custcat
				ORDER BY CUSTC_NAME";
		return $this->db->query($sql);
	}
	
	function get_AllDataC($PRJCODE, $search) // GOOD
	{
		$sql = "tbl_custcat
					WHERE CUSTC_CODE LIKE '%$search%' ESCAPE '!' OR CUSTC_NAME LIKE '%$search%' ESCAPE '!' OR CUSTC_DESC LIKE '%$search%' ESCAPE '!'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($PRJCODE, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_custcat
						WHERE CUSTC_CODE LIKE '%$search%' ESCAPE '!' OR CUSTC_NAME LIKE '%$search%' ESCAPE '!' OR CUSTC_DESC LIKE '%$search%' ESCAPE '!'
						ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT * FROM tbl_custcat
						WHERE CUSTC_CODE LIKE '%$search%' ESCAPE '!' OR CUSTC_NAME LIKE '%$search%' ESCAPE '!' OR CUSTC_DESC LIKE '%$search%' ESCAPE '!'";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_custcat
						WHERE CUSTC_CODE LIKE '%$search%' ESCAPE '!' OR CUSTC_NAME LIKE '%$search%' ESCAPE '!' OR CUSTC_DESC LIKE '%$search%' ESCAPE '!'
						ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT * FROM tbl_custcat
						WHERE CUSTC_CODE LIKE '%$search%' ESCAPE '!' OR CUSTC_NAME LIKE '%$search%' ESCAPE '!' OR CUSTC_DESC LIKE '%$search%' ESCAPE '!'
						LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_num_rowsSCAT($cinta) // G
	{	
		$sql	= "tbl_custcat WHERE CUSTC_CODE = '$cinta'";
		return $this->db->count_all($sql);
	}
	
	function add1($LinkAcc1) // G
	{
		$this->db->insert('tbl_link_account', $LinkAcc1);
	}
	
	function add2($LinkAcc2) // G
	{
		$this->db->insert('tbl_link_account', $LinkAcc2);
	}
	
	function upd1($LinkAcc1, $CUSTC_CODE, $LA_CATEG1) // G
	{
		$this->db->where('LA_ITM_CODE', $CUSTC_CODE);
		$this->db->where('LA_CATEG', $LA_CATEG1);
		$this->db->update('tbl_link_account', $LinkAcc1);
	}
	
	function upd2($LinkAcc2, $CUSTC_CODE, $LA_CATEG2) // G
	{
		$this->db->where('LA_ITM_CODE', $CUSTC_CODE);
		$this->db->where('LA_CATEG', $LA_CATEG2);
		$this->db->update('tbl_link_account', $LinkAcc2);
	}
	
	function add($custcat) // G
	{
		$this->db->insert('tbl_custcat', $custcat);
	}
	
	function get_custcat_by_code($CUSTC_CODE) // G
	{
		if($CUSTC_CODE == '')
		{
			$sql = "SELECT * FROM tbl_custcat";
		}
		else
		{
			$sql = "SELECT * FROM tbl_custcat WHERE CUSTC_CODE = '$CUSTC_CODE'";
		}
		return $this->db->query($sql);
	}
	
	function update($CUSTC_CODE, $custcat) // G
	{
		$this->db->where('CUSTC_CODE', $CUSTC_CODE);
		$this->db->update('tbl_custcat', $custcat);
	}
}
?>