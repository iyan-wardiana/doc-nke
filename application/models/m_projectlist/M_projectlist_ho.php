<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 19 Oktober 2017
 * File Name	= M_projectlist.php
 * Notes		= -
*/

class M_projectlist extends CI_Model
{
	public function __construct() // GOOD
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_AllDataC($DefEmp_ID, $search) // GOOD
	{
		$sql 	= "tbl_project_budg A WHERE A.PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
					AND A.BUDG_LEVEL = 2";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($DefEmp_ID, $search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_budg A 
						WHERE A.PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.BUDG_LEVEL = 2
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_budg A 
						WHERE A.PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.BUDG_LEVEL = 2
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT A.*
						FROM tbl_project_budg A 
						WHERE A.PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.BUDG_LEVEL = 2
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir
							LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT A.*
						FROM tbl_project_budg A 
						WHERE A.PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID') AND A.BUDG_LEVEL = 2
							AND (A.PRJCODE LIKE '%$search%' ESCAPE '!' OR A.PRJCODE_HO LIKE '%$search%' ESCAPE '!'
							OR A.PRJNAME LIKE '%$search%' ESCAPE '!' OR A.PRJCOST LIKE '%$search%' ESCAPE '!'
							OR A.PRJDATE LIKE '%$search%' ESCAPE '!' OR A.PRJEDAT LIKE '%$search%' ESCAPE '!') LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
	
	function count_all_project($DefEmp_ID)  // OK
	{
		$sql	= "tbl_project_budg WHERE PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
		return $this->db->count_all($sql);
	}
	
	function get_all_project($DefEmp_ID)  // OK
	{
		$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCODE_HO, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJOWN, A.PRJDATE, A.PRJEDAT, A.PRJCOST, 
					A.PRJLKOT, A.PRJCBNG, A.PRJCURR,
					A.CURRRATE, A.PRJSTAT, A.PRJNOTE, A.Patt_Year, A.Patt_Number
				FROM tbl_project_budg A 
				WHERE A.PRJCODE_HO IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
				ORDER BY A.PRJCODE";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
}
?>