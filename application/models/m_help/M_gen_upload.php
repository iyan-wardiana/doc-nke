<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 07 November 2017
 * File Name	= M_gen_upload.php
 * Function		= -
*/

class m_gen_upload extends CI_Model
{	
	function count_all_upl()
	{
		$sql	= "tbl_genfileupload";
		return $this->db->count_all($sql);
	}
	
	function get_all_genupload()
	{
		$sql = "SELECT A.*,
				B.First_Name, B.Middle_Name, B.Last_Name
				FROM tbl_genfileupload A
				INNER JOIN tbl_employee B ON A.UP_Emp = B.Emp_ID";
		return $this->db->query($sql);
	}
		
	function add($insGUp) // U
	{
		$this->db->insert('tbl_genfileupload', $insGUp);
	}
	
	function getAllFile()
	{
		return $this->db->count_all('tbl_genfileupload');
	}
	
	function getLastFile()
	{
		$sql = "SELECT FileUpName
				FROM tbl_genfileupload
				ORDER BY IDUpload DESC
				LIMIT 0 , 1";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_DN($txtSearch)
	{		
		$sql	= "tbl_genfileupload WHERE FileUpName LIKE '%$txtSearch%'";
			
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_AN($txtSearch)
	{		
		$sql	= "tbl_genfileupload A
					INNER JOIN tbl_employee B ON A.UP_Emp = B.Emp_ID	
					WHERE B.First_Name LIKE '%$txtSearch%' OR B.Middle_Name LIKE '%$txtSearch%' OR B.Last_Name LIKE '%$txtSearch%'";
			
		return $this->db->count_all($sql);
	}
	
	function count_all_num_rows_DocN($limit, $offset, $txtSearch)
	{
		$sql = "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name
				FROM tbl_genfileupload A
				INNER JOIN  tbl_employee B ON A.UP_Emp = B.Emp_ID	
				WHERE FileUpName LIKE '%$txtSearch%' ORDER BY FileUpName LIMIT $offset, $limit";
		
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_AutN($limit, $offset, $txtSearch)
	{
		$sql = "SELECT A.*, B.First_Name, B.Middle_Name, B.Last_Name
				FROM tbl_genfileupload A
				INNER JOIN tbl_employee B ON A.UP_Emp = B.Emp_ID	
				WHERE B.First_Name LIKE '%$txtSearch%' OR B.Middle_Name LIKE '%$txtSearch%' OR B.Last_Name LIKE '%$txtSearch%'";
		
		return $this->db->query($sql);
	}
}
?>