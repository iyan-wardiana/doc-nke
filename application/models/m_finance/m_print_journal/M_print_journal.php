<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 12 Februari 2017
 * File Name	= m_print_journal.php
 * Location		= -
*/
?>
<?php
class m_print_journal extends CI_Model
{
	var $table 		= 'tlpmhd';
	var $tableSPP	= 'tspphd';
	var $tableOPN	= 'topnhd';
	var $tableSPK	= 'tspkhd';
	var $tableVOC	= 'tvochd';
	var $tableSPKPr	= 'tbl_spkprint';
	
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
	
	// START LPM	
		function count_all_num_rows_lpm($PRJCODE) // U
		{
			$sql	= "lpmhd WHERE APPROVE = '1' AND PRJCODE = '$PRJCODE'";
			return $this->db->count_all($sql);
		}
		
		function get_last_ten_lpm($limit, $offset, $PRJCODE) // U
		{
			$sql = "SELECT * FROM lpmhd WHERE APPROVE = '1' AND PRJCODE = '$PRJCODE' LIMIT $offset, 100";
			return $this->db->query($sql);
		}
	// END LPM
	
	// SPP	
		function count_all_num_rows_spp($PRJCODE) // U
		{
			$sql	= "spphd WHERE APPROVE = '1' AND PRJCODE = '$PRJCODE'";
			return $this->db->count_all($sql);
		}
		
		function get_last_ten_spp($limit, $offset, $PRJCODE) // U
		{
			$sql = "SELECT * FROM spphd WHERE APPROVE = '1' AND PRJCODE = '$PRJCODE' LIMIT $offset, 100";
			return $this->db->query($sql);
		}
	// END SPP
	
	// VOC	
		function count_all_num_rows_voc($PRJCODE) // U
		{
			$sql	= "vochd WHERE APPROVE = '1' AND PRJCODE = '$PRJCODE'";
			return $this->db->count_all($sql);
		}
		
		function get_last_ten_voc($limit, $offset, $PRJCODE) // U
		{
			$sql = "SELECT * FROM vochd WHERE APPROVE = '1' AND PRJCODE = '$PRJCODE' LIMIT $offset, 100";
			return $this->db->query($sql);
		}
	// END VOC
	
	// VLK
		function count_all_num_rows_vlk($PRJCODE) // U
		{
			$sql	= "vlkhd WHERE APPROVE = '1' AND PRJCODE = '$PRJCODE'";
			return $this->db->count_all($sql);
		}
		
		function get_last_ten_vlk($limit, $offset, $PRJCODE) // U
		{
			$sql = "SELECT * FROM vlkhd WHERE APPROVE = '1' AND PRJCODE = '$PRJCODE' LIMIT $offset, 100";
			return $this->db->query($sql);
		}
	// END VLK
	
	// START OPN
		function count_all_num_rows_opn($PRJCODE) // U
		{
			$sql	= "topnhd WHERE APPROVE = '1' AND PRJCODE = '$PRJCODE'";
			return $this->db->count_all($sql);
		}
		
		function get_last_ten_opn($limit, $offset, $PRJCODE) // U
		{
			$sql = "SELECT * FROM topnhd WHERE APPROVE = '1' AND PRJCODE = '$PRJCODE' LIMIT $offset, 100";
			return $this->db->query($sql);
		}
	// END OPN
	
	// SPK
	function count_all_num_rows_spk()
	{
		return $this->db->count_all($this->tableSPK);
	}
	
	function get_last_ten_spk($limit, $offset)
	{
		$sql = "SELECT *
				FROM tspkhd
				ORDER BY spkcode
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function get_spkdata_by_code($SPKCODE)
	{
		$sql = "SELECT * FROM tspkhd WHERE SPKCODE = '$SPKCODE'";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_spkC($txtSearch)
	{
		$sql	= "tspkhd WHERE SPKCODE LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_SPKC($limit, $offset, $txtSearch)
	{
		$sql = "SELECT *
				FROM tspkhd WHERE SPKCODE LIKE '%$txtSearch%'
				ORDER BY SPKCODE
				LIMIT $offset, $limit";
		return $this->db->query($sql);
	}
	
	function getCountSPK($SPKCODE)
	{
		$sqlSPKC	= "tbl_spkprint WHERE SPKCODE = '$SPKCODE'";
		return $this->db->count_all($sqlSPKC);
	}
	
	function add($printSPK)
	{
		$this->db->insert($this->tableSPKPr, $printSPK);
	}
	
	function updateSPK($SPKCODE, $printSPK)
	{
		$this->db->where('SPKCODE', $SPKCODE);
		$this->db->update($this->tableSPKPr, $printSPK);
	}
	
	function getDataSPK($SPKCODE)
	{
		$sql = "SELECT * FROM tbl_spkprint WHERE SPKCODE = '$SPKCODE'";
		return $this->db->query($sql);
	}
	
	function getCountDataSPK($SPKCODE)
	{
		$sqlSPKC	= "tbl_spkprint WHERE SPKCODE = '$SPKCODE'";
		return $this->db->count_all($sqlSPKC);
	}
		
}
?>