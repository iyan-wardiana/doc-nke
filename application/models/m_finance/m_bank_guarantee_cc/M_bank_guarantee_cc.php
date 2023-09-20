<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 08 Maret 2017
 * File Name	= M_bank_guarantee_cc.php
 * Location		= -
*/
?>
<?php
class M_bank_guarantee_cc extends CI_Model
{
	function count_all_num_rows() // OK
	{
		return $this->db->count_all('tbl_bnkgrs');
	}
	
	function get_last_ten_bguarantee($empID) // OK
	{
		$empID = $this->session->userdata('Emp_ID');
		if($empID == 'SMR0001')
		{
			$sql = "SELECT *
					FROM tbl_bnkgrs
					ORDER BY grscode";
		}
		else
		{
			$sql = "SELECT *
					FROM tbl_bnkgrs
					ORDER BY grscode";
		}
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_duedate($dateNow2, $empID)
	{
		$empID = $this->session->userdata('Emp_ID');
		if($empID == 'SMR0001')
		{
			$sql	= "tbl_bnkgrs WHERE grsdate2 <= '$dateNow2'";
		}
		else
		{
			//$sql	= "tbl_bnkgrs WHERE emp_ID = '$empID' AND grsdate2 <= '$dateNow2'";
			$sql	= "tbl_bnkgrs WHERE grsdate2 <= '$dateNow2'";
		}
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_bguarantee_duedate($limit, $offset, $dateNow2, $empID)
	{
		$empID = $this->session->userdata('Emp_ID');
		if($empID == 'SMR0001')
		{
			$sql = "SELECT *
					FROM tbl_bnkgrs WHERE grsdate2 <= '$dateNow2'
					ORDER BY grscode
					LIMIT $offset, $limit";
		}
		else
		{
			/*$sql = "SELECT *
					FROM tbl_bnkgrs WHERE emp_ID = '$empID' AND grsdate2 <= '$dateNow2'
					ORDER BY grscode
					LIMIT $offset, $limit";*/
			$sql = "SELECT *
					FROM tbl_bnkgrs WHERE grsdate2 <= '$dateNow2'
					ORDER BY grscode
					LIMIT $offset, $limit";
		}
		return $this->db->query($sql);
	}
}
?>