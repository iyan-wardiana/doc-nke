<?php
/* 
	* Author		= Dian Hermanto
	* Create Date	= 23 Maret 2017
	* File Name	= M_vendcat.php
	* Location		= -
*/
?>
<?php
class M_vendcat extends CI_Model
{
	function count_all_num_rows() // OK
	{
		return $this->db->count_all('tbl_vendcat');
	}
		
	function get_AllDataC($search) // GOOD
	{
		$sql = "tbl_vendcat
				WHERE VendCat_Code LIKE '%$search%' ESCAPE '!' OR VendCat_Name LIKE '%$search%' ESCAPE '!'
					OR VendCat_Desc LIKE '%$search%' ESCAPE '!'";
		return $this->db->count_all($sql);
	}
		
	function get_AllDataL($search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT *
						FROM tbl_vendcat
						WHERE VendCat_Code LIKE '%$search%' ESCAPE '!' OR VendCat_Name LIKE '%$search%' ESCAPE '!'
							OR VendCat_Desc LIKE '%$search%' ESCAPE '!'
						ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT *
						FROM tbl_vendcat
						WHERE VendCat_Code LIKE '%$search%' ESCAPE '!' OR VendCat_Name LIKE '%$search%' ESCAPE '!'
							OR VendCat_Desc LIKE '%$search%' ESCAPE '!'";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT *
						FROM tbl_vendcat
						WHERE VendCat_Code LIKE '%$search%' ESCAPE '!' OR VendCat_Name LIKE '%$search%' ESCAPE '!'
							OR VendCat_Desc LIKE '%$search%' ESCAPE '!'
						ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT *
						FROM tbl_vendcat
						WHERE VendCat_Code LIKE '%$search%' ESCAPE '!' OR VendCat_Name LIKE '%$search%' ESCAPE '!'
							OR VendCat_Desc LIKE '%$search%' ESCAPE '!'
						LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_last_ten_vendcat() // OK
	{
		$sql = "SELECT VendCat_Code, VendCat_Name, VendCat_Desc
				FROM tbl_vendcat
				ORDER BY VendCat_Name";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsVCAT($cinta) // OK
	{	
		$sql	= "tbl_vendcat WHERE VendCat_Code = '$cinta'";
		return $this->db->count_all($sql);
	}
	
	function add($vendcat) // OK
	{
		$this->db->insert('tbl_vendcat', $vendcat);
	}
	
	function get_vendcat_by_code($VendCat_Code) // OK
	{
		if($VendCat_Code == '')
		{
			$sql = "SELECT * FROM tbl_vendcat";
		}
		else
		{
			$sql = "SELECT * FROM tbl_vendcat WHERE VendCat_Code = '$VendCat_Code'";
		}
		return $this->db->query($sql);
	}
	
	function update($VendCat_Code, $vendcat) // OK
	{
		$this->db->where('VendCat_Code', $VendCat_Code);
		$this->db->update('tbl_vendcat', $vendcat);
	}
	
	function add1($LinkAcc1) // G
	{
		$this->db->insert('tbl_link_account', $LinkAcc1);
	}
	
	function add2($LinkAcc2) // G
	{
		$this->db->insert('tbl_link_account', $LinkAcc2);
	}
	
	function upd1($LinkAcc1, $VendCat_Code, $LA_CATEG1) // G
	{
		$this->db->where('LA_ITM_CODE', $VendCat_Code);
		$this->db->where('LA_CATEG', $LA_CATEG1);
		$this->db->update('tbl_link_account', $LinkAcc1);
	}
	
	function upd2($LinkAcc2, $VendCat_Code, $LA_CATEG2) // G
	{
		$this->db->where('LA_ITM_CODE', $VendCat_Code);
		$this->db->where('LA_CATEG', $LA_CATEG2);
		$this->db->update('tbl_link_account', $LinkAcc2);
	}
}
?>