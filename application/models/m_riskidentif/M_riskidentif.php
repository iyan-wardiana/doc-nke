<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 13 April 2017
 * File Name	= M_riskidentif.php
 * Location		= -
*/
?>
<?php
class M_riskidentif extends CI_Model
{
	function count_all_num_rows($DefEmp_ID) // OK
	{
		//$sql	= "tbl_riskidentif WHERE EMP_ID = '$DefEmp_ID'";
		$sql	= "tbl_riskidentif";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_risk($DefEmp_ID) // OK
	{
		/*$sql = "SELECT * FROM tbl_riskidentif
				WHERE EMP_ID = '$DefEmp_ID'
				ORDER BY RID_CODE";*/
		$sql = "SELECT * FROM tbl_riskidentif
				ORDER BY RID_CODE";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$sql = "SELECT * FROM tbl_docpattern WHERE menu_code = '$MenuCode'";
		return $this->db->query($sql);
	}
	
	function add($riskidentif) // OK
	{
		$this->db->insert('tbl_riskidentif', $riskidentif);
	}
	
	function get_riskidentif_by_code($RID_CODE) // OK
	{
		$sql = "SELECT * FROM tbl_riskidentif WHERE RID_CODE = '$RID_CODE'";
		return $this->db->query($sql);
	}
	
	function update($RID_CODE, $riskidentif) // OK
	{
		$this->db->where('RID_CODE', $RID_CODE);
		$this->db->update('tbl_riskidentif', $riskidentif);
	}
	
	function deleteDetail1($RID_CODE, $DefEmp_ID) // OK
	{
		$this->db->where('RIDD_CODE1', $RID_CODE);
		$this->db->where('EMP_ID1', $DefEmp_ID);
		$this->db->delete('tbl_riskdescdet');
	}
	
	function deleteDetail2($RID_CODE, $DefEmp_ID) // OK
	{
		$this->db->where('RIDD_CODE2', $RID_CODE);
		$this->db->where('EMP_ID2', $DefEmp_ID);
		$this->db->delete('tbl_riskimpactdet');
	}
	
	function deleteDetail3($RID_CODE, $DefEmp_ID) // OK
	{
		$this->db->where('RIDD_CODE3', $RID_CODE);
		$this->db->where('EMP_ID3', $DefEmp_ID);
		$this->db->delete('tbl_riskpolicydet');
	}
}
?>