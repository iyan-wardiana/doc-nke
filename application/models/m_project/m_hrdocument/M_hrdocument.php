<?php
/* 
 * Author		= Dian Hermanto
 * Create Date	= 9 Februari 2017
 * File Name	= M_hrdocument.php
 * Location		= -
*/

class M_hrdocument extends CI_Model
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
	
	function get_last_ten_project($limit, $offset, $DefEmp_ID) // OK
	{
		$sql = "SELECT A.proj_Code AS PRJCODE, B.proj_Number, B.proj_Number, B.PRJCODE, B.PRJCNUM, B.PRJNAME, B.PRJLOCT, B.PRJOWN, B.PRJDATE, B.PRJEDAT, 
					B.PRJCOST, B.PRJLKOT, B.PRJCBNG, B.PRJSTAT
				FROM tbl_employee_proj A
					INNER JOIN tbl_project B ON A.proj_Code = B.PRJCODE
				WHERE 
					B.PRJSTAT = 1
					AND A.Emp_ID = '$DefEmp_ID'
				ORDER BY B.PRJCODE";
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsProjDOC($PRJCODE, $selTYPEDOC1, $txtSearch) // HOLD
	{
		if($txtSearch != '')
		{
			/*$sql	= "tbl_hrdoc_header A
						LEFT JOIN  	tbl_employee B ON A.HRDOCEMPID = B.Emp_ID
						INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
							AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND DOCCODE = '$selTYPEDOC1'
						AND HRDOCCODE LIKE '%$txtSearch%'";*/
			$sql	= "tbl_hrdoc_header A
						LEFT JOIN  	tbl_employee B ON A.HRDOCEMPID = B.Emp_ID
						INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
							AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		else
		{
			/*$sql	= "tbl_hrdoc_header A
						LEFT JOIN  	tbl_employee B ON A.HRDOCEMPID = B.Emp_ID
						INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
							AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND DOCCODE = '$selTYPEDOC1'";*/
			$sql	= "tbl_hrdoc_header A
						LEFT JOIN  	tbl_employee B ON A.HRDOCEMPID = B.Emp_ID
						INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
							AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'";
		}
		return $this->db->count_all($sql);
	}
	
	function count_all_num_DokHR($doc_code) // OK
	{
	
		$sql	= "tbl_hrdoc_header WHERE DOCCODE = '$doc_code'";					
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_DokHR($doc_code) // OK
	{
		$sql = "SELECT * FROM tbl_hrdoc_header WHERE DOCCODE = '$doc_code' ORDER BY TRXDATE ASC";
		return $this->db->query($sql);
	}
	
	function getDataDocPat($MenuCode) // OK
	{
		$this->db->select('Pattern_Code,Pattern_Position,Pattern_YearAktive,Pattern_MonthAktive,Pattern_DateAktive,Pattern_Length,useYear,useMonth,useDate');
		$this->db->from('tbl_docpattern');
		$this->db->where('menu_code', $MenuCode);
		return $this->db->get();
	}
	
	function add($dataINSDOC) // USE
	{
		$this->db->insert('tbl_hrdoc_header', $dataINSDOC);
	}
	
	function get_DOC_by_number($HRDOCNO) // OK
	{
		$sql = "SELECT * FROM tbl_hrdoc_header
				WHERE HRDOCNO = '$HRDOCNO'";
		return $this->db->query($sql);
	}
	
	function get_DOC_Type($DOCCODE) // OK
	{
		$sql = "SELECT doc_name FROM tbl_document
				WHERE doc_code = '$DOCCODE'";
		return $this->db->query($sql);
	}
	
	function get_DOC_by_number1($HRDOCNO) // OK
	{
		$sql = "SELECT * FROM tbl_hrdoc_header
				WHERE HRDOCNO = '$HRDOCNO'";
		return $this->db->query($sql);
	}
	
	function update($HRDOCNO, $dataUPDDOC)
	{
		$this->db->where('HRDOCNO', $HRDOCNO);
		$this->db->update('tbl_hrdoc_header', $dataUPDDOC);
	}
	
	function get_last_ten_projHRDOC($PRJCODE, $limit, $offset, $selTYPEDOC1, $txtSearch) // USE
	{
		if($txtSearch != '')
		{
			/*$sql = "SELECT DISTINCT A.*, B.First_Name, B.Middle_Name, B.Last_Name, 
					C.proj_Number, C.PRJNAME
					FROM tbl_hrdoc_header A
					LEFT JOIN  	tbl_employee B ON A.HRDOCEMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
						AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND DOCCODE = '$selTYPEDOC1'
						AND HRDOCCODE LIKE '%$txtSearch%'
					ORDER BY A.HRDOCCODE ASC";*/
			$sql = "SELECT DISTINCT A.*, B.First_Name, B.Middle_Name, B.Last_Name, 
					C.proj_Number, C.PRJNAME
					FROM tbl_hrdoc_header A
					LEFT JOIN  	tbl_employee B ON A.HRDOCEMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
						AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
					ORDER BY A.HRDOCCODE ASC";
		}
		else
		{
			/*$sql = "SELECT DISTINCT A.*, B.First_Name, B.Middle_Name, B.Last_Name, 
					C.proj_Number, C.PRJNAME
					FROM tbl_hrdoc_header A
					LEFT JOIN  	tbl_employee B ON A.HRDOCEMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
						AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
						AND DOCCODE = '$selTYPEDOC1'
					ORDER BY A.HRDOCCODE ASC";*/
			$sql = "SELECT DISTINCT A.*, B.First_Name, B.Middle_Name, B.Last_Name, 
					C.proj_Number, C.PRJNAME
					FROM tbl_hrdoc_header A
					LEFT JOIN  	tbl_employee B ON A.HRDOCEMPID = B.Emp_ID
					INNER JOIN 	tbl_project C ON A.PRJCODE = C.PRJCODE
						AND C.PRJCODE = '$PRJCODE'
					WHERE A.PRJCODE = '$PRJCODE'
					ORDER BY A.HRDOCCODE ASC";
		}
		return $this->db->query($sql);
	}
	
	function count_all_num_rowsProject() // USE
	{
		return $this->db->count_all('tbl_project');
	}
	
	function viewProject() // USE
	{
		$sql = "SELECT proj_Number, PRJCODE, PRJNAME
				FROM tbl_project
				ORDER BY PRJCODE ASC";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_PNo($txtSearch, $DefEmp_ID) // USE
	{
		$sql	= "tbl_employee_proj A
					INNER JOIN tbl_project B ON A.proj_Code = B.PRJCODE
				WHERE 
					B.PRJSTAT = 1
					AND A.Emp_ID = '$DefEmp_ID'
					AND A.proj_Code LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project_PNo($limit, $offset, $txtSearch, $DefEmp_ID) // USE
	{
		$sql = "SELECT A.proj_Code AS PRJCODE, B.proj_Number, B.PRJCODE, B.PRJCNUM, B.PRJDATE, B.PRJEDAT, B.PRJSTAT, B.PRJNAME
				FROM tbl_employee_proj A
					INNER JOIN tbl_project B ON A.proj_Code = B.PRJCODE
				WHERE 
					B.PRJSTAT = 1
					AND A.Emp_ID = '$DefEmp_ID'
					AND A.proj_Code LIKE '%$txtSearch%'
				ORDER BY A.proj_Code";
		return $this->db->query($sql);
	}
	
	function count_all_num_rows_PNm($txtSearch, $DefEmp_ID) // USE
	{
		$sql	= "tbl_employee_proj A
					INNER JOIN tbl_project B ON A.proj_Code = B.PRJCODE
				WHERE 
					B.PRJSTAT = 1
					AND A.Emp_ID = '$DefEmp_ID'
					AND B.PRJNAME LIKE '%$txtSearch%'";
		return $this->db->count_all($sql);
	}
	
	function get_last_ten_project_PNm($limit, $offset, $txtSearch) // USE
	{
		$sql = "SELECTA.proj_Code AS PRJCODE, B.proj_Number, B.PRJCODE, B.PRJCNUM, B.PRJDATE, B.PRJEDAT, B.PRJSTAT, B.PRJNAME
				FROM tbl_employee_proj A
					INNER JOIN tbl_project B ON A.proj_Code = B.PRJCODE
				WHERE 
					B.PRJSTAT = 1
					AND A.Emp_ID = '$DefEmp_ID'
					AND A.PRJNAME LIKE '%$txtSearch%'
				ORDER BY A.proj_Code";
		return $this->db->query($sql);
	}
	
	function get_last_ten_projHRDOC1() // USE
	{
		$sql = "SELECT A.*
				FROM tbl_hrdoc_header A
				ORDER BY A.ID ASC";
		return $this->db->query($sql);
	}
	
	/*function getAllFile($HRDOCNO) // USE
	{
		$sql	= "tgenfileupload WHERE pay_code = '$HRDOCNO'";
		return $this->db->count_all($sql);
	}*/
	
	function getAllFile($HRDOCNO) // USE
	{
		$sql	= "tbl_hrdoc_header WHERE HRDOCNO = '$HRDOCNO'";
		return $this->db->count_all($sql);
	}
	
	function getLastFile() // USE
	{
		$sql = "SELECT FileUpName
				FROM tgenfileupload
				WHERE pay_code = '$HRDOCNO'
				ORDER BY IDUpload DESC
				LIMIT 0 , 1";
		return $this->db->query($sql);
	}
	
	function count_all_emp() // OK
	{
		return $this->db->count_all('tbl_employee');
		$sql	= "tbl_employee WHERE Employee_status = '1'";
		return $this->db->count_all($sql);
	}
	
	function viewAllEmp() // OK
	{
		$sql	= "SELECT Emp_ID, EmpNoIdentity, First_Name, Middle_Name, Last_Name
					FROM tbl_employee WHERE Employee_status = '1'
					ORDER BY First_Name ASC";
		return $this->db->query($sql);
	}
}
?>