<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 23 Maret 2017
 * File Name	= M_joblist.php
 * Location		= -
*/
?>
<?php
class M_joblist extends CI_Model
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
	
	function count_all_schedule($PRJCODE) // OK
	{
		$sql = "tbl_joblist WHERE PRJCODE = '$PRJCODE' AND JOBTYPE = 'S'";
		return $this->db->count_all($sql);
	}
	
	function get_all_joblist($PRJCODE) // OK
	{
		$sql = "SELECT *
				FROM tbl_joblist WHERE PRJCODE = '$PRJCODE' AND JOBTYPE = 'S'
				ORDER BY JOBCODEID";
		return $this->db->query($sql);
	}
	
	function get_project_name($PRJCODE) // OK
	{
		$sql = "SELECT * FROM tbl_project WHERE PRJCODE = '$PRJCODE'";			
		return $this->db->query($sql);
	}
	
	function count_all_JOB($PRJCODE, $JOB_CODE) // OK
	{	
		$sql	= "tbl_joblist WHERE PRJCODE = '$PRJCODE' AND JOBCOD1 = '$JOB_CODE'";
		return $this->db->count_all($sql);
	}
	
	function add($joblist) // OK
	{
		$this->db->insert('tbl_joblist', $joblist);
	}
	
	function get_joblist_by_code($JOBCODEID) // OK
	{
		if($JOBCODEID == '')
		{
			$sql = "SELECT * FROM tbl_joblist";
		}
		else
		{
			$sql = "SELECT * FROM tbl_joblist WHERE JOBCODEID = '$JOBCODEID'";
		}
		return $this->db->query($sql);
	}
	
	function update($JOBCODEID, $joblist) // OK
	{
		$this->db->where('JOBCODEID', $JOBCODEID);
		$this->db->update('tbl_joblist', $joblist);
	}
}
?>