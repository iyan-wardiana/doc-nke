<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Maret 2020
 * File Name	= M_tsemp.php
 * Location		= -
*/
?>
<?php
class M_tsemp extends CI_Model
{
	var $table = 'tbl_employee_ts';
	
	function count_allTS($Emp_ID) // USED
	{
		$sql = "tbl_employee_ts WHERE EMP_ID = '$Emp_ID'";
		return $this->db->count_all($sql);
	}
	
	function get_allTS($limit, $offset, $Emp_ID) // USED
	{
		$sql = "SELECT * FROM tbl_employee_ts WHERE EMP_ID = '$Emp_ID'
				ORDER BY EMPTS_DATE
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function add($jobEmp) // USED
	{
		$this->db->insert($this->table, $jobEmp);
	}
	
	function get_TSEmp_Bycode($EMPTS_CODE) // U
	{
		$sql = "SELECT * FROM tbl_employee_ts WHERE EMPTS_CODE = '$EMPTS_CODE'";
		return $this->db->query($sql);
	}
	
	function update($EMPTS_CODE, $jobEmp) // USED
	{
		$this->db->where('EMPTS_CODE', $EMPTS_CODE);
		$this->db->update('tbl_employee_ts', $jobEmp);
	}
	
	function addAbs($absInp) // USED
	{
		$this->db->insert('tbl_absensi', $absInp);
	}
}
?>