<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 15 Maret 2017
 * File Name	= M_qhsedocument.php
 * Location		= -
*/
?>
<?php
class M_qhsedocument extends CI_Model
{
	function count_all_num_rows($DefEmp_ID) // OK
	{
		$sql	= "tbl_employee_proj A
					INNER JOIN tbl_project B ON A.proj_Code = B.PRJCODE
					WHERE 
						B.PRJSTAT = 1
						AND A.Emp_ID = '$DefEmp_ID'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project($DefEmp_ID) // OK
	{
		$sql = "SELECT A.proj_Code AS PRJCODE, B.proj_Number, B.proj_Number, B.PRJCODE, B.PRJCNUM, B.PRJNAME, B.PRJLOCT,
					B.PRJOWN, B.PRJDATE, B.PRJEDAT, 
					B.PRJCOST, B.PRJLKOT, B.PRJCBNG, B.PRJSTAT
				FROM tbl_employee_proj A
					INNER JOIN tbl_project B ON A.proj_Code = B.PRJCODE
				WHERE 
					B.PRJSTAT = 1
					AND A.Emp_ID = '$DefEmp_ID'
				ORDER BY B.PRJCODE";
		return $this->db->query($sql);
	}
	
	function count_all_num_DokQHSE($doc_code) // OK
	{
	
		$sql	= "tbl_qhsedoc_header WHERE DOCCODE = '$doc_code' AND HRDOCSTAT = '1'";					
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_DokQHSE($doc_code) // OK
	{
		$sql = "SELECT * FROM tbl_qhsedoc_header WHERE DOCCODE = '$doc_code' AND HRDOCSTAT = '1' ORDER BY TRXDATE ASC";
		return $this->db->query($sql);
	}
	
	function get_DOC_Type($DOCCODE) // OK
	{
		$sql = "SELECT doc_name FROM tbl_document
				WHERE doc_code = '$DOCCODE' AND isShow = 1";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($dataINSDOC) // OK
	{
		$this->db->insert('tbl_qhsedoc_header', $dataINSDOC);
	}
	
	function get_DOC_by_number($HRDOCNO) // OK
	{
		$sql = "SELECT * FROM tbl_qhsedoc_header
				WHERE HRDOCNO = '$HRDOCNO'";
		return $this->db->query($sql);
	}
	
	function update($HRDOCNO, $dataUPDDOC) // OK
	{
		$this->db->where('HRDOCNO', $HRDOCNO);
		$this->db->update('tbl_qhsedoc_header', $dataUPDDOC);
	}
					
	function UpdateOriginal($HRDOCID) // U
	{
		$sql = "UPDATE tbl_qhsedoc_header SET HRDOCSTAT = '5' WHERE HRDOCID = $HRDOCID";
		return $this->db->query($sql);
	}
}
?>