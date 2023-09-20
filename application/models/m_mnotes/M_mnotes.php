<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Maret 2020
 * File Name	= M_mnotes.php
 * Location		= -
*/
?>
<?php
class M_mnotes extends CI_Model
{
	var $table = 'tbl_notulen_meetting';
	
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
	
	function get_TSEmp_Bycode($NTLN_CODE) // U
	{
		return $this->db->get_where($this->table, array('NTLN_CODE' => $NTLN_CODE));
	}
	
	function update($NTLN_CODE, $jobEmp) // USED
	{
		$this->db->where('NTLN_CODE', $NTLN_CODE);
		$this->db->update($this->table, $jobEmp);
	}

	function get_AllDataC($search) // GOOD
	{
		$sql = "tbl_notulen_meetting
				WHERE (NTLN_CODE LIKE '%$search%' ESCAPE '!' OR NTLN_DATE LIKE '%$search%' ESCAPE '!'
					OR NTLN_START LIKE '%$search%' ESCAPE '!' OR NTLN_END LIKE '%$search%' ESCAPE '!' 
					OR NTLN_LOC LIKE '%$search%' ESCAPE '!' OR NTLN_TOPIC LIKE '%$search%' ESCAPE '!' 
					OR NTLN_DESC LIKE '%$search%' ESCAPE '!' OR NTLN_USER LIKE '%$search%' ESCAPE '!' 
					OR NTLN_THEORY LIKE '%$search%' ESCAPE '!')";
		return $this->db->count_all($sql);
	}
	
	function get_AllDataL($search, $length, $start, $order, $dir) // GOOD
	{
		if($length == -1)
		{
			if($order !=null)
			{
				$sql = "SELECT *
						FROM tbl_notulen_meetting
						WHERE (NTLN_CODE LIKE '%$search%' ESCAPE '!' OR NTLN_DATE LIKE '%$search%' ESCAPE '!'
							OR NTLN_START LIKE '%$search%' ESCAPE '!' OR NTLN_END LIKE '%$search%' ESCAPE '!' 
							OR NTLN_LOC LIKE '%$search%' ESCAPE '!' OR NTLN_TOPIC LIKE '%$search%' ESCAPE '!' 
							OR NTLN_DESC LIKE '%$search%' ESCAPE '!' OR NTLN_USER LIKE '%$search%' ESCAPE '!' 
							OR NTLN_THEORY LIKE '%$search%' ESCAPE '!') ORDER BY $order $dir";
			}
			else
			{
				$sql = "SELECT *
						FROM tbl_notulen_meetting
						WHERE (NTLN_CODE LIKE '%$search%' ESCAPE '!' OR NTLN_DATE LIKE '%$search%' ESCAPE '!'
							OR NTLN_START LIKE '%$search%' ESCAPE '!' OR NTLN_END LIKE '%$search%' ESCAPE '!' 
							OR NTLN_LOC LIKE '%$search%' ESCAPE '!' OR NTLN_TOPIC LIKE '%$search%' ESCAPE '!' 
							OR NTLN_DESC LIKE '%$search%' ESCAPE '!' OR NTLN_USER LIKE '%$search%' ESCAPE '!' 
							OR NTLN_THEORY LIKE '%$search%' ESCAPE '!')";
			}
			return $this->db->query($sql);
		}
		else
		{
			if($order !=null)
			{
				$sql = "SELECT *
						FROM tbl_notulen_meetting
						WHERE (NTLN_CODE LIKE '%$search%' ESCAPE '!' OR NTLN_DATE LIKE '%$search%' ESCAPE '!'
							OR NTLN_START LIKE '%$search%' ESCAPE '!' OR NTLN_END LIKE '%$search%' ESCAPE '!' 
							OR NTLN_LOC LIKE '%$search%' ESCAPE '!' OR NTLN_TOPIC LIKE '%$search%' ESCAPE '!' 
							OR NTLN_DESC LIKE '%$search%' ESCAPE '!' OR NTLN_USER LIKE '%$search%' ESCAPE '!' 
							OR NTLN_THEORY LIKE '%$search%' ESCAPE '!') 
							ORDER BY $order $dir LIMIT $start, $length";
			}
			else
			{
				$sql = "SELECT *
						FROM tbl_notulen_meetting
						WHERE (NTLN_CODE LIKE '%$search%' ESCAPE '!' OR NTLN_DATE LIKE '%$search%' ESCAPE '!'
							OR NTLN_START LIKE '%$search%' ESCAPE '!' OR NTLN_END LIKE '%$search%' ESCAPE '!' 
							OR NTLN_LOC LIKE '%$search%' ESCAPE '!' OR NTLN_TOPIC LIKE '%$search%' ESCAPE '!' 
							OR NTLN_DESC LIKE '%$search%' ESCAPE '!' OR NTLN_USER LIKE '%$search%' ESCAPE '!' 
							OR NTLN_THEORY LIKE '%$search%' ESCAPE '!') 
							LIMIT $start, $length";
			}
			return $this->db->query($sql);
		}
	}
}
?>