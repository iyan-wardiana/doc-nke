<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 16 Februari 2017
 * File Name	= M_profile.php
 * Location		= -
*/
?>
<?php
class M_profile extends CI_Model
{	
	function count_all_num_rows($Emp_ID) // U
	{
		return $this->db->count_all("tbl_employee WHERE Emp_ID != '$Emp_ID'");
	}
	
	function get_all_employee($Emp_ID) // U
	{
		$query = "SELECT * FROM tbl_employee WHERE Emp_ID != '$Emp_ID'";
		return $this->db->query($query);
	}
	
	function count_Emp_ID($Emp_ID) // USE
	{
		$sqlAC 			= "tbl_employee WHERE Emp_ID = '$Emp_ID'";
		return $this->db->count_all($sqlAC);
	}
	
	function get_count_Emp_ID($Emp_ID) // USE
	{		
		$sql = "SELECT * FROM tbl_employee WHERE Emp_ID = '$Emp_ID'";
		return $this->db->query($sql);
	}
	
	function update($Emp_ID, $employee) // U
	{
		$this->db->where('Emp_ID', $Emp_ID);
		$this->db->update('tbl_employee', $employee);
	}
	
	function update2($Emp_ID, $employeeCp) // U
	{
		$this->db->where('NK', $Emp_ID);
		$this->db->update('others', $employeeCp);
	}
	
	function updateProfPict($Emp_ID, $nameFile, $fileInpName) // U
	{
		$sqlEMP	= "tbl_employee_img WHERE imgemp_empid = '$Emp_ID'";
		$resEMP	= $this->db->count_all($sqlEMP);
		if($resEMP == 0)
		{
			$insIMG	= "INSERT INTO tbl_employee_img (imgemp_empid, imgemp_filename, imgemp_filenameX)
						VALUES ('$Emp_ID', '$fileInpName', '$nameFile')";
			$this->db->query($insIMG);
		}
		else
		{
			$updIMG	= "UPDATE tbl_employee_img SET imgemp_filename = '$fileInpName', imgemp_filenameX = '$nameFile' 
						WHERE imgemp_empid = '$Emp_ID'";
			$this->db->query($updIMG);
		}
	}
}
?>