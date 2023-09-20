<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 17 Februari 2017
 * File Name	= M_project_recomendation.php
 * Location		= -
*/
?>
<?php
class M_project_recomendation extends CI_Model
{	
	function count_all_num_rows() // U
	{
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];	
		$FlagUSER 		= $this->session->userdata['FlagUSER'];
		if($FlagUSER == 'MRK_SIPIL')
		{
			$sql		= "tbl_project_recom WHERE PRJ_JNS = 'S'";
		}
		else
		{
			$sql		= "tbl_project_recom";
		}
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_projrecom() // U
	{
		$DefEmp_ID 		= $this->session->userdata['Emp_ID'];
		$FlagUSER 		= $this->session->userdata['FlagUSER'];
		if($FlagUSER == 'MRK_SIPIL')
		{
			$sql 			= "SELECT * FROM tbl_project_recom WHERE PRJ_JNS = 'S' ORDER BY REC_CODE";
		}
		else
		{
			$sql 			= "SELECT * FROM tbl_project_recom ORDER BY REC_CODE";
		}
		return $this->db->query($sql);
	}
	
	function add($projectrecom) // U
	{
		$this->db->insert('tbl_project_recom', $projectrecom);
	}
	
	function count_all_num_rowsProj($REC_NO) // U
	{
		$sql	= "tbl_project_recom WHERE REC_NO = '$REC_NO'";
		return $this->db->count_all($sql);
	}
	
	function get_projrecom_bycode($REC_CODE) // U
	{	
		$sql 				= "SELECT * FROM tbl_project_recom WHERE REC_CODE = '$REC_CODE' ORDER BY REC_CODE";
		return $this->db->query($sql);
	}
	
	function update($REC_CODE, $projectrecom) // U
	{
		$this->db->where('REC_CODE', $REC_CODE);
		$this->db->update('tbl_project_recom', $projectrecom);
	}
	
	function getDataDocPat($MenuCode) // U
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function addRecHist($recomHist) // U
	{
		$this->db->insert('tbl_project_recom_hist', $recomHist);
	}
}
?>