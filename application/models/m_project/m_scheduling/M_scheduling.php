<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Maret 2017
 * File Name	= M_scheduling.php
 * Location		= -
*/
?>
<?php
class M_scheduling extends CI_Model
{	
	function count_all_project() // OK
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];	
		$sql		= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project() // OK
	{
		$DefEmp_ID 	= $this->session->userdata['Emp_ID'];		
		$sql 		= "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, 
							A.PRJCOST, A.PRJCOST2, A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
							A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
						FROM tbl_project A 
						WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
						ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function count_all_schedule() // 
	{
		return $this->db->count_all('tbl_schedule');
	}
	
	function get_all_schedule() // 
	{
		$sql = "SELECT VendCat_Code, VendCat_Name, VendCat_Desc
				FROM tbl_schedule
				ORDER BY VendCat_Name";
		return $this->db->query($sql);
	}
	
	function count_all_scheduleVCAT($cinta) // 
	{	
		$sql	= "tbl_schedule WHERE VendCat_Code = '$cinta'";
		return $this->db->count_all($sql);
	}
	
	function add($vendcat) // 
	{
		$this->db->insert('tbl_schedule', $vendcat);
	}
	
	function get_vendcat_by_code($VendCat_Code) // 
	{
		if($VendCat_Code == '')
		{
			$sql = "SELECT * FROM tbl_schedule";
		}
		else
		{
			$sql = "SELECT * FROM tbl_schedule WHERE VendCat_Code = '$VendCat_Code'";
		}
		return $this->db->query($sql);
	}
	
	function update($VendCat_Code, $vendcat) // 
	{
		$this->db->where('VendCat_Code', $VendCat_Code);
		$this->db->update('tbl_schedule', $vendcat);
	}
}
?>