<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 14 Juli 2017
 * File Name	= M_boq.php
 * Location		= -
*/

class M_boq extends CI_Model
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
	
	function count_all_boq_hist($PRJCODE) // OK
	{
		//return $this->db->count_all('tbl_boq_hist');
		$sql = "tbl_boq_hist WHERE BOQH_PRJCODE = '$PRJCODE'";
		return $this->db->count_all($sql);
	}
	
	function get_all_boq_hist($PRJCODE) // 
	{
		$sql = "SELECT *
				FROM tbl_boq_hist WHERE BOQH_PRJCODE = '$PRJCODE'
				ORDER BY BOQH_DATE ASC";
		return $this->db->query($sql);
	}
	
	function add($BoQHist) // OK
	{
		$this->db->insert('tbl_boq_hist', $BoQHist);
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
	
	function updateStat() // 
	{
		$sql = "UPDATE tbl_boq_hist SET BOQH_STAT = 0";
		$this->db->query($sql);
	}
}
?>