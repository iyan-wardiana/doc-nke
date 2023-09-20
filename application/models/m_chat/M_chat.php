<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 04 April 2017
 * File Name	= m_chat.php
 * Location		= -
*/

class M_chat extends CI_Model
{
	function count_all_inbox() // U
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$sql		= "tbl_mailbox  WHERE MB_TO = '$DefEmp_ID'"; 	// menghitung semua email menuju user aktif
		return $this->db->count_all($sql);
	}
	
	function count_all_Draft() // U
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$sql		= "tbl_mailbox  WHERE MB_FROM = '$DefEmp_ID' AND MB_STATUS = 3"; 	// menghitung semua email draft
		return $this->db->count_all($sql);
	}
	
	function count_all_Junk() // HOLD
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$sql		= "tbl_mailbox  WHERE MB_TO = '$DefEmp_ID' AND MB_STATUS = 4"; 	// menghitung semua email menuju user aktif
		return $this->db->count_all($sql);
	}
	
	function count_all_Trash() // U
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$sql		= "tbl_mailbox  WHERE MB_FROM = '$DefEmp_ID' AND MB_STATUS = 5"; 	// menghitung semua email draft
		return $this->db->count_all($sql);
	}
	
	function get_all_mail_inbox() // U
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];		
		$sql 		= "SELECT A.MB_CODE, A.MB_PARENTC, A.MB_SUBJECT, A.MB_DATE, A.MB_READD, A.MB_FROM, A.MB_TO, A.MB_MESSAGE, A.MB_STATUS,
							B.First_Name, B.Last_Name
						FROM tbl_mailbox A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.MB_FROM 
						WHERE A.MB_TO = '$DefEmp_ID'
						ORDER BY A.MB_DATE DESC"; 					// menampilkan semua email menuju user aktif
		return $this->db->query($sql);
	}
	
	function count_all_sent() // U
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];
		$sql		= "tbl_mailbox  WHERE MB_FROM = '$DefEmp_ID' AND MB_STATUS != 3"; 	// menghitung semua email dari user aktif
		return $this->db->count_all($sql);
	}
	
	function get_all_mail_sent() // U
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];		
		$sql 		= "SELECT A.MB_CODE, A.MB_PARENTC, A.MB_SUBJECT, A.MB_DATE, A.MB_READD, A.MB_FROM, A.MB_TO, A.MB_MESSAGE, A.MB_STATUS,
							B.First_Name, B.Last_Name
						FROM tbl_mailbox A 
							INNER JOIN tbl_employee B ON B.Emp_ID = A.MB_TO 
						WHERE A.MB_FROM = '$DefEmp_ID'
						ORDER BY A.MB_DATE DESC";					// menampilkan semua email dari user aktif
		return $this->db->query($sql);
	}
}
?>