<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 18 Maret 2020
 * File Name	= M_a553sm.php
 * Location		= -
*/
?>
<?php
class M_a553sm extends CI_Model
{
	var $table = 'tbl_assesment';
	
	function count_allTS($Emp_ID, $ISAPPROVE) // USED
	{
		if($ISAPPROVE == 1)
			$sql = "tbl_assesment";
		else
			$sql = "tbl_assesment WHERE EMP_ID = '$Emp_ID'";

		return $this->db->count_all($sql);
	}
	
	function get_allTS($limit, $offset, $Emp_ID, $ISAPPROVE) // USED
	{
		if($ISAPPROVE == 1)
			$sql = "SELECT * FROM tbl_assesment ORDER BY ASSM_DATE";
		else
			$sql = "SELECT * FROM tbl_assesment WHERE EMP_ID = '$Emp_ID' ORDER BY ASSM_DATE";

		return $this->db->query($sql);
	}
	
	function add($AssEmp) // USED
	{
		$this->db->insert($this->table, $AssEmp);
	}
	
	function get_ASEmp_Bycode($ASSM_CODE) // U
	{
		$sql = "SELECT * FROM tbl_assesment WHERE ASSM_CODE = '$ASSM_CODE'";
		return $this->db->query($sql);
	}
	
	function update($ASSM_CODE, $jobEmp) // USED
	{
		$this->db->where('ASSM_CODE', $ASSM_CODE);
		$this->db->update('tbl_assesment', $jobEmp);
	}
}
?>