<?php
/*
 * Author		= Dian Hermanto
 * Create Date	= 12 Februari 2017
 * File Name	= M_outapprove.php
 * Location		= -
*/
?>
<?php
class M_outapprove extends CI_Model
{
	// START PROJECT
		function count_all_project($DefEmp_ID) // U
		{
			$sql	= "tbl_project WHERE PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')";
			return $this->db->count_all($sql);
		}
		
		function get_last_ten_project($limit, $offset, $DefEmp_ID) // U
		{
			$sql = "SELECT A.proj_Number, A.PRJCODE, A.PRJCNUM, A.PRJNAME, A.PRJLOCT, A.PRJCOST, A.PRJDATE, A.PRJEDAT, A.PRJSTAT
					FROM tbl_project A
					WHERE A.PRJCODE IN (SELECT proj_Code FROM tbl_employee_proj WHERE Emp_ID = '$DefEmp_ID')
					ORDER BY A.PRJCODE";
			return $this->db->query($sql);
		}	
	// END PROJECT
	
	function count_all_num_rows($Emp_DeptCode)
	{
		$empID = $this->session->userdata('Emp_ID');
		$LHint = $this->session->userdata('log_passHint');
		$viewCond = 1;
		if($LHint == 'DH' || $LHint == 'RN' || $LHint == 'HR' || $LHint == 'LN' || $LHint == 'PRY')
		{
			$sql	= "tout_approve";																	// ALL
		}
		elseif($LHint == 'DL' || $LHint == 'NT' || $LHint == 'TY' || $LHint == 'RY' || $LHint == 'TF')
		{
			$sql	= "tout_approve WHERE OA_Category IN ('SPPHD', 'SPKHD')";								// SPP, SPK
		}
		elseif($LHint == 'MD' || $LHint == 'MG' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR')
		{
			$sql	= "tout_approve WHERE OA_Category IN ('LPMHD', 'OPHD', 'OPNHD', 'VOCHD', 'VOTHD')";			// LPM, OP, OPN, VOC, VOT
		}
		elseif($LHint == 'UP' || $LHint == 'EL')
		{
			$sql	= "tout_approve WHERE OA_Category NOT IN ('PD_HD', 'DP_HD')";								// ALL, EXCEPT PD, DP
		}
		elseif($LHint == 'OP')
		{
			$sql	= "tout_approve WHERE OA_Category NOT IN ('OPHD', 'OPNHD')";								// OP
		}
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_outapprove($limit, $offset, $empID, $Emp_DeptCode)
	{
		$empID = $this->session->userdata('Emp_ID');
		$LHint = $this->session->userdata('log_passHint');
		$viewCond = 1;
		if($LHint == 'DH' || $LHint == 'RN' || $LHint == 'HR' || $LHint == 'LN' || $LHint == 'PRY')
		{
			$sql	= "SELECT * FROM tout_approve ORDER BY OA_Category LIMIT $offset, $limit";										// ALL
		}
		elseif($LHint == 'DL' || $LHint == 'NT' || $LHint == 'TY' || $LHint == 'RY' || $LHint == 'TF')
		{
			$sql	= "SELECT * FROM tout_approve WHERE OA_Category IN ('SPPHD', 'SPKHD')
						ORDER BY OA_Category LIMIT $offset, $limit";																// SPP, SPK
		}
		elseif($LHint == 'MD' || $LHint == 'MG' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR')
		{
			$sql	= "SELECT * FROM tout_approve WHERE OA_Category IN ('LPMHD', 'OPHD', 'OPNHD', 'VOCHD', 'VOTHD')
						ORDER BY OA_Category LIMIT $offset, $limit";																// LPM, OP, OPN, VOC, VOT
		}
		elseif($LHint == 'UP' || $LHint == 'EL')
		{
			$sql	= "SELECT * FROM tout_approve WHERE OA_Category NOT IN ('PD_HD', 'DP_HD')
						ORDER BY OA_Category LIMIT $offset, $limit";																// ALL, EXCEPT PD, DP
		}
		elseif($LHint == 'OP')
		{
			$sql	= "SELECT * FROM tout_approve WHERE OA_Category IN ('OPHD', 'OPNHD')
						ORDER BY OA_Category LIMIT $offset, $limit";																// ALL, EXCEPT PD, DP
		}
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_src($selSearchType, $txtSearch, $Emp_DeptCode)
	{
		$empID = $this->session->userdata('Emp_ID');
		$LHint = $this->session->userdata('log_passHint');
		$viewCond = 1;
		if($selSearchType == "all")
		{
			if($LHint == 'DH' || $LHint == 'RN' || $LHint == 'HR' || $LHint == 'LN' || $LHint == 'PRY')
			{
				$sql	= "tout_approve WHERE OA_Code LIKE '%$txtSearch%'";																// ALL
			}
			elseif($LHint == 'DL' || $LHint == 'NT' || $LHint == 'TY' || $LHint == 'RY' || $LHint == 'TF')
			{
				$sql	= "tout_approve WHERE OA_Category IN ('SPPHD', 'SPKHD') AND OA_Code LIKE '%$txtSearch%' f";							// SPP, SPK
			}
			elseif($LHint == 'MD' || $LHint == 'MG' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR')
			{
				$sql	= "tout_approve WHERE OA_Category IN ('LPMHD', 'OPHD', 'OPNHD', 'VOCHD', 'VOTHD') AND OA_Code LIKE '%$txtSearch%'";		// LPM, OP, OPN, VOC, VOT
			}
			elseif($LHint == 'UP' || $LHint == 'EL')
			{
				$sql	= "tout_approve WHERE OA_Category NOT IN ('PD_HD', 'DP_HD') AND OA_Code LIKE '%$txtSearch%'";							// ALL, EXCEPT PD, DP
			}
			elseif($LHint == 'OP')
			{
				$sql	= "tout_approve WHERE OA_Category IN ('OPHD', 'OPNHD') AND OA_Code LIKE '%$txtSearch%'";		// OP
			}
			return $this->db->count_all($sql);
		}
		else
		{
			if($LHint == 'DH' || $LHint == 'RN' || $LHint == 'HR' || $LHint == 'LN' || $LHint == 'PRY')
			{
				$sql	= "tout_approve WHERE OA_Category = '$selSearchType' AND OA_Code LIKE '%$txtSearch%'";														// ALL
			}
			elseif($LHint == 'DL' || $LHint == 'NT' || $LHint == 'TY' || $LHint == 'RY' || $LHint == 'TF')
			{
				$sql	= "tout_approve WHERE OA_Category IN ('SPPHD', 'SPKHD') AND OA_Category = '$selSearchType' AND OA_Code LIKE '%$txtSearch%'";					// SPP, SPK
			}
			elseif($LHint == 'MD' || $LHint == 'MG' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR')
			{
				$sql	= "tout_approve WHERE OA_Category IN ('LPMHD', 'OPHD', 'OPNHD', 'VOCHD', 'VOTHD') AND OA_Category = '$selSearchType' AND OA_Code LIKE '%$txtSearch%'";// LPM, OP, OPN, VOC, VOT
			}
			elseif($LHint == 'UP' || $LHint == 'EL')
			{
				$sql	= "tout_approve WHERE OA_Category NOT IN ('PD_HD', 'DP_HD') AND OA_Category = '$selSearchType' AND OA_Code LIKE '%$txtSearch%'";				// ALL, EXCEPT PD, DP
			}
			elseif($LHint == 'OP')
			{
				$sql	= "tout_approve WHERE OA_Category IN ('OPHD', 'OPNHD') AND OA_Category = '$selSearchType' AND OA_Code LIKE '%$txtSearch%'";// OP
			}
			return $this->db->count_all($sql);
		}
	}
	
	function get_last_ten_outapprove_src($limit, $offset, $selSearchType, $txtSearch, $Emp_DeptCode)
	{
		$empID = $this->session->userdata('Emp_ID');
		$LHint = $this->session->userdata('log_passHint');
		$viewCond = 1;
		
		if($selSearchType == "all")
		{
			if($LHint == 'DH' || $LHint == 'RN' || $LHint == 'HR' || $LHint == 'LN' || $LHint == 'PRY')
			{
				$sql	= "SELECT * FROM tout_approve WHERE OA_Code LIKE '%$txtSearch%' ORDER BY OA_Category LIMIT $offset, $limit";	// ALL
			}
			elseif($LHint == 'DL' || $LHint == 'NT' || $LHint == 'TY' || $LHint == 'RY' || $LHint == 'TF')
			{
				$sql	= "SELECT * FROM tout_approve WHERE OA_Category IN ('SPPHD', 'SPKHD') AND OA_Code LIKE '%$txtSearch%'
							ORDER BY OA_Category LIMIT $offset, $limit";																// SPP, SPK
			}
			elseif($LHint == 'MD' || $LHint == 'MG' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR')
			{
				$sql	= "SELECT * FROM tout_approve WHERE OA_Category IN ('LPMHD', 'OPHD', 'OPNHD', 'VOCHD', 'VOTHD') AND OA_Code LIKE '%$txtSearch%'
							ORDER BY OA_Category LIMIT $offset, $limit";																// LPM, OP, OPN, VOC, VOT
			}
			elseif($LHint == 'UP' || $LHint == 'EL')
			{
				$sql	= "SELECT * FROM tout_approve WHERE OA_Category NOT IN ('PD_HD', 'DP_HD') AND OA_Code LIKE '%$txtSearch%'
							ORDER BY OA_Category LIMIT $offset, $limit";																// ALL, EXCEPT PD, DP
			}
			elseif($LHint == 'OP')
			{
				$sql	= "SELECT * FROM tout_approve WHERE OA_Category IN ('OPHD', 'OPNHD') AND OA_Code LIKE '%$txtSearch%'
							ORDER BY OA_Category LIMIT $offset, $limit";																// OP
			}
			return $this->db->query($sql);
		}
		else
		{
			if($LHint == 'DH' || $LHint == 'RN' || $LHint == 'HR' || $LHint == 'LN' || $LHint == 'PRY')
			{
				$sql	= "SELECT * FROM tout_approve WHERE OA_Category = '$selSearchType' AND OA_Code LIKE '%$txtSearch%'
							ORDER BY OA_Category LIMIT $offset, $limit";																// ALL
			}
			elseif($LHint == 'DL' || $LHint == 'NT' || $LHint == 'TY' || $LHint == 'RY' || $LHint == 'TF')
			{
				$sql	= "SELECT * FROM tout_approve WHERE OA_Category IN ('SPPHD', 'SPKHD') AND OA_Category = '$selSearchType' AND OA_Code LIKE '%$txtSearch%'
							ORDER BY OA_Category LIMIT $offset, $limit";																// SPP, SPK
			}
			elseif($LHint == 'MD' || $LHint == 'MG' || $LHint == 'WS' || $LHint == 'WM' || $LHint == 'LIA' || $LHint == 'MR')
			{
				$sql	= "SELECT * FROM tout_approve WHERE OA_Category IN ('LPMHD', 'OPHD', 'OPNHD', 'VOCHD', 'VOTHD') AND OA_Category = '$selSearchType' AND OA_Code LIKE '%$txtSearch%'
							ORDER BY OA_Category LIMIT $offset, $limit";																// LPM, OP, OPN, VOC, VOT
			}
			elseif($LHint == 'UP' || $LHint == 'EL')
			{
				$sql	= "SELECT * FROM tout_approve WHERE OA_Category NOT IN ('PD_HD', 'DP_HD') AND OA_Category = '$selSearchType' AND OA_Code LIKE '%$txtSearch%'
							ORDER BY OA_Category LIMIT $offset, $limit";																// ALL, EXCEPT PD, DP
			}
			elseif($LHint == 'OP')
			{
				$sql	= "SELECT * FROM tout_approve WHERE OA_Category IN ('OPHD', 'OPNHD') AND OA_Code LIKE '%$txtSearch%'
							ORDER BY OA_Category LIMIT $offset, $limit";																// OP
			}
			return $this->db->query($sql);
		}
	}
}
?>