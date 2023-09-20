<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 22 Februari 2017
 * File Name	= M_mailbox.php
 * Location		= -
*/
?>
<?php
class M_mailbox extends CI_Model
{
	function count_all_inbox($DefEmp_ID) // U
	{
		//$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$sql		= "tbl_mailbox  WHERE MB_TO_ID = '$DefEmp_ID'"; 	// menghitung semua email menuju user aktif
		return $this->db->count_all($sql);
	}
	
	function count_all_Draft($DefEmp_ID) // U
	{
		//$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$sql		= "tbl_mailbox  WHERE MB_FROM_ID = '$DefEmp_ID' AND MB_STATUS = 3"; 	// menghitung semua email draft
		return $this->db->count_all($sql);
	}
	
	function count_all_Junk($DefEmp_ID) // HOLD
	{
		//$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$sql		= "tbl_mailbox  WHERE MB_TO_ID = '$DefEmp_ID' AND MB_STATUS = 4"; 	// menghitung semua email menuju user aktif
		return $this->db->count_all($sql);
	}
	
	function count_all_Trash($DefEmp_ID) // U
	{
		//$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$sql		= "tbl_mailbox  WHERE MB_FROM_ID = '$DefEmp_ID' AND MB_STATUS = 5"; 	// menghitung semua email draft
		return $this->db->count_all($sql);
	}
	
	function get_all_mail_inbox($DefEmp_ID) // U
	{
		//$DefEmp_ID 	= $this->session->userdata['Emp_ID'];		
		$sql 		= "SELECT A.MB_CODE, A.MB_PARENTC, A.MB_SUBJECT, A.MB_DATE, A.MB_READD, A.MB_FROM, A.MB_TO, A.MB_MESSAGE, A.MB_STATUS,
							B.First_Name, B.Last_Name
						FROM tbl_mailbox A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.MB_FROM 
						WHERE A.MB_TO_ID = '$DefEmp_ID'
						ORDER BY A.MB_DATE DESC"; 					// menampilkan semua email menuju user aktif
		return $this->db->query($sql);
	}
	
	function count_all_sent($DefEmp_ID) // U
	{
		$sql		= "tbl_mailbox WHERE MB_FROM_ID = '$DefEmp_ID' AND MB_STATUS != 3"; 	// menghitung semua email dari user aktif
		return $this->db->count_all($sql);
	}
	
	function get_all_mail_sent($DefEmp_ID) // U
	{
		//$DefEmp_ID 	= $this->session->userdata['Emp_ID'];		
		$sql 		= "SELECT A.MB_CODE, A.MB_PARENTC, A.MB_SUBJECT, A.MB_DATE, A.MB_READD, A.MB_FROM, A.MB_TO, A.MB_MESSAGE, A.MB_STATUS,
							B.First_Name, B.Last_Name
						FROM tbl_mailbox A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.MB_TO_ID 
						WHERE A.MB_FROM_ID = '$DefEmp_ID'
						ORDER BY A.MB_DATE DESC";					// menampilkan semua email dari user aktif
		return $this->db->query($sql);
	}
	
	function add($insMail) // U
	{
		$this->db->insert('tbl_mailbox', $insMail);
	}
}
?>