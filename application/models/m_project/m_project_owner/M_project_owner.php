<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 9 Februari 2017
 * File Name	= M_project_owner.php
 * Location		= -
*/
?>
<?php
class M_project_owner extends CI_Model
{
	var $table = 'tbl_owner';
	
	function count_all_num_rows() // U
	{
		return $this->db->count_all('tbl_owner');
	}
	
	function get_AllDataC($search) // GOOD
	{
		$sql = "tbl_owner
				WHERE 	own_Code LIKE '%$search%' ESCAPE '!' OR own_Name LIKE '%$search%' ESCAPE '!' OR own_Add1 LIKE '%$search%' ESCAPE '!'
						OR own_Telp LIKE '%$search%' ESCAPE '!' OR own_CP_Name LIKE '%$search%' ESCAPE '!' OR own_CP LIKE '%$search%' ESCAPE '!'";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_owner
						WHERE 	own_Code LIKE '%$search%' ESCAPE '!' OR own_Name LIKE '%$search%' ESCAPE '!' OR own_Add1 LIKE '%$search%' ESCAPE '!'
								OR own_Telp LIKE '%$search%' ESCAPE '!' OR own_CP_Name LIKE '%$search%' ESCAPE '!' OR own_CP LIKE '%$search%' ESCAPE '!'
								ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT * FROM tbl_owner
						WHERE 	own_Code LIKE '%$search%' ESCAPE '!' OR own_Name LIKE '%$search%' ESCAPE '!' OR own_Add1 LIKE '%$search%' ESCAPE '!'
								OR own_Telp LIKE '%$search%' ESCAPE '!' OR own_CP_Name LIKE '%$search%' ESCAPE '!' OR own_CP LIKE '%$search%' ESCAPE '!'";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT * FROM tbl_owner
						WHERE 	own_Code LIKE '%$search%' ESCAPE '!' OR own_Name LIKE '%$search%' ESCAPE '!' OR own_Add1 LIKE '%$search%' ESCAPE '!'
								OR own_Telp LIKE '%$search%' ESCAPE '!' OR own_CP_Name LIKE '%$search%' ESCAPE '!' OR own_CP LIKE '%$search%' ESCAPE '!'
								ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT * FROM tbl_owner
						WHERE 	own_Code LIKE '%$search%' ESCAPE '!' OR own_Name LIKE '%$search%' ESCAPE '!' OR own_Add1 LIKE '%$search%' ESCAPE '!'
								OR own_Telp LIKE '%$search%' ESCAPE '!' OR own_CP_Name LIKE '%$search%' ESCAPE '!' OR own_CP LIKE '%$search%' ESCAPE '!'
								LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function get_last_ten_owner($limit, $offset) // U
	{		
		$sql = "SELECT * FROM tbl_owner
				ORDER BY own_Name
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_VCode($txtSearch, $OwnStat) // U
	{
		$sql	= "tbl_owner WHERE own_Code LIKE '%$txtSearch%' AND own_Status = '$OwnStat'";
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_VName($txtSearch, $OwnStat) // U
	{
		$sql	= "tbl_owner WHERE own_Name LIKE '%$txtSearch%' AND own_Status = '$OwnStat'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_owner_VCode($limit, $offset, $txtSearch, $OwnStat) // U
	{		
		$sql = "SELECT * FROM tbl_owner WHERE own_Code LIKE '%$txtSearch%' AND own_Status = '$OwnStat'
				ORDER BY own_Name
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_last_ten_owner_VName($limit, $offset, $txtSearch, $OwnStat) // U
	{		
		$sql = "SELECT * FROM tbl_owner WHERE own_Name LIKE '%$txtSearch%' AND own_Status = '$OwnStat'
				ORDER BY own_Name
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // U
	{
		$sql = "SELECT * FROM tbl_docpattern WHERE menu_code = '$MenuCode'";
		return $this->db->query($sql);
	}
	
	function get_owner_by_code($own_Code) // U
	{
		$sql = "SELECT * FROM tbl_owner WHERE own_Code = '$own_Code'";
		return $this->db->query($sql);
	}
	
	function add($owner) // U
	{
		$this->db->insert($this->table, $owner);
	}
	
	function update($own_Code, $owner) // U
	{
		$this->db->where('own_Code', $own_Code);
		$this->db->update('tbl_owner', $owner);
	}
	
	function delete($own_Code)
	{
		// Customer can not be deleted. So, just change status
		$this->db->where('own_Code', $own_Code);
		$this->db->update($this->table);
	}
	
	function add3($customerImg) // USE
	{
		$this->db->insert('tbl_owner_img', $customerImg);
	}
	
	function updateProfPict($own_Code, $nameFile, $fileInpName) // G
	{
		$sqlCustC	= "tbl_owner_img WHERE IMGO_CUSTCODE = '$own_Code'";
		$resCustC	= $this->db->count_all($sqlCustC);
		if($resCustC == 0)
		{
			$InsProfPict	= "INSERT INTO tbl_owner_img (IMGO_CUSTCODE, IMGO_FILENAME, IMGO_FILENAMEX)
								VALUES ('$own_Code', '$fileInpName', '$nameFile')";
			$this->db->query($InsProfPict);
		}
		else
		{
			$UpProfPict		= "UPDATE tbl_owner_img SET IMGO_FILENAME = '$fileInpName', IMGO_FILENAMEX = '$nameFile' 
								WHERE IMGO_CUSTCODE = '$own_Code'";
			$this->db->query($UpProfPict);
		}
	}
}
?>