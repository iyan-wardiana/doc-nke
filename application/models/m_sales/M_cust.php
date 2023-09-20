<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2018
 * File Name	= M_cust.php
 * Location		= -
*/

class M_cust extends CI_Model
{
	function count_all_cust() // G
	{
		return $this->db->count_all('tbl_customer');
	}
	
	function get_AllDataC($search) // GOOD
	{
		$sql = "tbl_customer
				WHERE 	CUST_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' OR CUST_ADD1 LIKE '%$search%' ESCAPE '!'
						OR CUST_KOTA LIKE '%$search%' ESCAPE '!' OR CUST_TELP LIKE '%$search%' ESCAPE '!' OR CUST_MAIL LIKE '%$search%' ESCAPE '!'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_customer
						WHERE 	CUST_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' OR CUST_ADD1 LIKE '%$search%' ESCAPE '!'
								OR CUST_KOTA LIKE '%$search%' ESCAPE '!' OR CUST_TELP LIKE '%$search%' ESCAPE '!' OR CUST_MAIL LIKE '%$search%' ESCAPE '!'
								ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT * FROM tbl_customer
						WHERE 	CUST_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' OR CUST_ADD1 LIKE '%$search%' ESCAPE '!'
								OR CUST_KOTA LIKE '%$search%' ESCAPE '!' OR CUST_TELP LIKE '%$search%' ESCAPE '!' OR CUST_MAIL LIKE '%$search%' ESCAPE '!'";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_customer
						WHERE 	CUST_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' OR CUST_ADD1 LIKE '%$search%' ESCAPE '!'
								OR CUST_KOTA LIKE '%$search%' ESCAPE '!' OR CUST_TELP LIKE '%$search%' ESCAPE '!' OR CUST_MAIL LIKE '%$search%' ESCAPE '!'
								ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT * FROM tbl_customer
						WHERE 	CUST_CODE LIKE '%$search%' ESCAPE '!' OR CUST_DESC LIKE '%$search%' ESCAPE '!' OR CUST_ADD1 LIKE '%$search%' ESCAPE '!'
								OR CUST_KOTA LIKE '%$search%' ESCAPE '!' OR CUST_TELP LIKE '%$search%' ESCAPE '!' OR CUST_MAIL LIKE '%$search%' ESCAPE '!'
								LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_all_cust() // G
	{
		$sql = "SELECT * FROM tbl_customer";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // U
	{
		$sql = "SELECT * FROM tbl_docpattern WHERE menu_code = '$MenuCode'";
		return $this->db->query($sql);
	}
	
	function add($customer) // G
	{
		$this->db->insert('tbl_customer', $customer);
	}
	
	function add3($customerImg) // USE
	{
		$this->db->insert('tbl_customer_img', $customerImg);
	}
	
	function get_cust_by_code($CUST_CODE) // G
	{
		if($CUST_CODE == '')
		{
			$sql = "SELECT * FROM tbl_customer";
		}
		else
		{
			$sql = "SELECT * FROM tbl_customer WHERE CUST_CODE = '$CUST_CODE'";
		}
		return $this->db->query($sql);
	}
	
	function update($CUST_CODE, $vendor) // G
	{
		$this->db->where('CUST_CODE', $CUST_CODE);
		$this->db->update('tbl_customer', $vendor);
	}
	
	function updateProfPict($CUST_CODE, $nameFile, $fileInpName) // G
	{
		$sqlCustC	= "tbl_customer_img WHERE IMGC_CUSTCODE = '$CUST_CODE'";
		$resCustC	= $this->db->count_all($sqlCustC);
		if($resCustC == 0)
		{
			$InsProfPict	= "INSERT INTO tbl_customer_img (IMGC_CUSTCODE, IMGC_FILENAME, IMGC_FILENAMEX)
								VALUES ('$CUST_CODE', '$fileInpName', '$nameFile')";
			$this->db->query($InsProfPict);
		}
		else
		{
			$UpProfPict		= "UPDATE tbl_customer_img SET IMGC_FILENAME = '$fileInpName', IMGC_FILENAMEX = '$nameFile' 
								WHERE IMGC_CUSTCODE = '$CUST_CODE'";
			$this->db->query($UpProfPict);
		}
	}
}
?>